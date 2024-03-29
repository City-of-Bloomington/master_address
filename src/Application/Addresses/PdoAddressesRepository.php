<?php
/**
 * @copyright 2018-2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Addresses;
use Domain\Addresses\DataStorage\AddressesRepository;

use Aura\SqlQuery\Common\SelectInterface;

use Application\PdoRepository;
use Application\Locations\PdoLocationsRepository;
use Application\Subunits\PdoSubunitsRepository;

use Domain\Addresses\Entities\Address;
use Domain\Addresses\UseCases\Add\AddRequest;
use Domain\Addresses\UseCases\Correct\CorrectRequest;
use Domain\Addresses\UseCases\Readdress\ReaddressRequest;
use Domain\Addresses\UseCases\Renumber\RenumberRequest;
use Domain\Addresses\UseCases\Update\Request as UpdateRequest;

use Domain\Locations\Entities\Location;
use Domain\Streets\Metadata as Street;

use Domain\Logs\Entities\ChangeLogEntry;
use Domain\Logs\Metadata as ChangeLog;

class PdoAddressesRepository extends PdoRepository implements AddressesRepository
{
    use \Domain\Logs\DataStorage\ChangeLogTrait;
    use \Domain\Logs\DataStorage\StatusLogTrait;

    const TABLE       = 'addresses';
    const LOG_TYPE    = 'address';

    public static $DEFAULT_SORT = [
        'street_name',
        'street_suffix_code',
        'street_direction',
        'street_post_direction',
        'street_number'
    ];

    /**
     * Maps response fieldnames to the names used in the database
     */
    public static $fieldmap = [
        'id'                   => ['prefix'=>'a', 'dbName'=>'id'                  ],
        'street_number_prefix' => ['prefix'=>'a', 'dbName'=>'street_number_prefix'],
        'street_number'        => ['prefix'=>'a', 'dbName'=>'street_number'       ],
        'street_number_suffix' => ['prefix'=>'a', 'dbName'=>'street_number_suffix'],
        'address2'             => ['prefix'=>'a', 'dbName'=>'address2'            ],
        'address_type'         => ['prefix'=>'a', 'dbName'=>'address_type'        ],
        'street_id'            => ['prefix'=>'a', 'dbName'=>'street_id'           ],
        'jurisdiction_id'      => ['prefix'=>'a', 'dbName'=>'jurisdiction_id'     ],
        'township_id'          => ['prefix'=>'a', 'dbName'=>'township_id'         ],
        'subdivision_id'       => ['prefix'=>'a', 'dbName'=>'subdivision_id'      ],
        'plat_id'              => ['prefix'=>'a', 'dbName'=>'plat_id'             ],
        'section'              => ['prefix'=>'a', 'dbName'=>'section'             ],
        'quarter_section'      => ['prefix'=>'a', 'dbName'=>'quarter_section'     ],
        'plat_lot_number'      => ['prefix'=>'a', 'dbName'=>'plat_lot_number'     ],
        'city'                 => ['prefix'=>'a', 'dbName'=>'city'                ],
        'state'                => ['prefix'=>'a', 'dbName'=>'state'               ],
        'zip'                  => ['prefix'=>'a', 'dbName'=>'zip'                 ],
        'zipplus4'             => ['prefix'=>'a', 'dbName'=>'zipplus4'            ],
        'state_plane_x'        => ['prefix'=>'a', 'dbName'=>'state_plane_x'       ],
        'state_plane_y'        => ['prefix'=>'a', 'dbName'=>'state_plane_y'       ],
        'latitude'             => ['prefix'=>'a', 'dbName'=>'latitude'            ],
        'longitude'            => ['prefix'=>'a', 'dbName'=>'longitude'           ],
        'usng'                 => ['prefix'=>'a', 'dbName'=>'usng'                ],
        'notes'                => ['prefix'=>'a', 'dbName'=>'notes'               ],

        'jurisdiction_name'    => ['prefix'=>'j',   'dbName'=>'name'],
        'township_name'        => ['prefix'=>'t',   'dbName'=>'name'],
        'plat_name'            => ['prefix'=>'p',   'dbName'=>'name'],
        'subdivision_name'     => ['prefix'=>'sub', 'dbName'=>'name'],

        'street_direction'      => ['prefix'=>'sn', 'dbName'=>'direction'     ],
        'street_name'           => ['prefix'=>'sn', 'dbName'=>'name'          ],
        'street_post_direction' => ['prefix'=>'sn', 'dbName'=>'post_direction'],
        'street_suffix_code'    => ['prefix'=>'st', 'dbName'=>'code'          ],

        'status' => ['prefix' =>'status', 'dbName'=>'status'],

        'location_id'     => ['prefix' => 'l', 'dbName'=>'location_id'  ],
        'locationType_id' => ['prefix' => 'l', 'dbName'=>'type_id'      ],
        'mailable'        => ['prefix' => 'l', 'dbName'=>'mailable'     ],
        'occupiable'      => ['prefix' => 'l', 'dbName'=>'occupiable'   ],
        'group_quarter'   => ['prefix' => 'l', 'dbName'=>'group_quarter'],
        'active'          => ['prefix' => 'l', 'dbName'=>'active'       ]
    ];

    public function columns(): array
    {
        static $cols = [];
        if (!$cols) {
            foreach (self::$fieldmap as $responseName=>$map) {
                $cols[] = "$map[prefix].$map[dbName] as $responseName";
            }
        }
        return $cols;
    }

    private function baseSelect(): SelectInterface
    {
        $select = $this->queryFactory->newSelect();
        $select->cols($this->columns())
               ->from(self::TABLE.' a')
               ->join('LEFT', 'townships            t', 'a.township_id=t.id')
               ->join('LEFT', 'jurisdictions        j', 'a.jurisdiction_id=j.id')
               ->join('LEFT', 'plats                p', 'a.plat_id=p.id')
               ->join('LEFT', 'subdivisions       sub', 'a.subdivision_id=sub.id')
               ->join('LEFT', 'streets              s', 'a.street_id=s.id')
               ->join('LEFT', 'street_designations sd', 's.id=sd.street_id and sd.type_id='.Street::TYPE_STREET)
               ->join('LEFT', 'street_names        sn', 'sd.street_name_id=sn.id')
               ->join('LEFT', 'street_types        st', 'sn.suffix_code_id=st.id')
               ->join('LEFT', 'locations            l', 'a.id=l.address_id and l.subunit_id is null and l.active')
               ->joinSubSelect('LEFT', 'select distinct on (address_id) address_id, status from address_status order by address_id, start_date desc', 'status', 'a.id=status.address_id');

        return $select;
    }

    private static function hydrateAddress(array $row): Address
    {
        $a = new Address($row);
        if (isset($row['subunit_count'])) { $a->subunit_count = (int)$row['subunit_count']; }
        return $a;
    }

    /**
     * Load an Address object from the database
     */
    public function load(int $address_id): Address
    {
        $select = $this->baseSelect();
        $select->where('a.id=?', $address_id);

        $result = parent::performSelect($select);
        if (count($result['rows'])) {

            return self::hydrateAddress($result['rows'][0]);
        }
        throw new \Exception('addresses/unknown');
    }

    /**
     * Find addresses with exact match to fields
     */
    public function find(array $fields, ?array $order=null, ?int $itemsPerPage=null, ?int $currentPage=null): array
    {
        $select = $this->baseSelect();
        foreach ($fields as $f=>$v) {
            if (!empty($v)) {
                if (array_key_exists($f, self::$fieldmap)) {
                    $column = self::$fieldmap[$f]['prefix'].'.'.self::$fieldmap[$f]['dbName'];
                    $select->where("$column=?", $v);
                }
            }
        }
        return $this->doSelect($select, $order, $itemsPerPage, $currentPage);
    }

    /**
     * Find addresses with wildcard matching of text fields
     */
    public function search(array $fields, ?array $order=null, ?int $itemsPerPage=null, ?int $currentPage=null): array
    {
        $select  = $this->baseSelect();
        $cols    = $this->columns();
        $cols[]  = "( select count(*)
                    from subunits       x
                    join (select distinct on (subunit_id) subunit_id, status from subunit_status order by subunit_id, start_date desc) as xs on x.id=xs.subunit_id
                    where x.address_id=a.id) as subunit_count";
        $select->cols($cols);
        foreach ($fields as $f=>$v) {
            if (!empty($v)) {
                if (array_key_exists($f, self::$fieldmap)) {
                    $column = self::$fieldmap[$f]['prefix'].'.'.self::$fieldmap[$f]['dbName'];
                    switch ($f) {
                        case 'street_number':
                            // Postgres requires converting int to varchar before
                            // doing a like comparison.
                            // Unfortunately, the Aura SqlQuery butchers the ANSI-92
                            // cast(street_number as varchar).  So, for now, we're
                            // using the Postgres specific ::varchar syntax for
                            // type casting.
                            $select->where("$column::varchar like ?", "$v%");
                        break;
                        case 'street_name':
                            $select->where("lower($column) like ?", strtolower("%$v%"));
                        break;

                        default:
                            $select->where("$column=?", $v);
                    }
                }
            }
        }
        if (!empty($fields['block_start']) && !empty($fields['block_end'])) {
            $select->where('a.street_number between ? and ?', $fields['block_start'], $fields['block_end']);
        }
        return $this->doSelect($select, $order, $itemsPerPage, $currentPage);
    }

    private function doSelect(SelectInterface $select, ?array $order=null, ?int $itemsPerPage=null, ?int $currentPage=null): array
    {
        $select->orderBy(self::$DEFAULT_SORT);
        $result = parent::performSelect($select, $itemsPerPage, $currentPage);

        $addresses = [];
        foreach ($result['rows'] as $r) { $addresses[] = self::hydrateAddress($r); }
        $result['rows'] = $addresses;
        return $result;
    }

    /**
     * Return all location rows for any location_id on this address
     *
     * Locations can have multiple address and addresses can have multiple
     * locations.  We need to be able to know all the other addresses
     * that are related to an address via the locations.
     *
     * This function returns all related location rows, not just the
     * location rows with this address_id.
     *
     * The return array should match the data returned from LocationsRepository::find()
     *
     * @return array   An array of Location entities
     */
    public function findLocations(int $address_id): array
    {
        $locations = [];
        // Performance Note:
        //
        // It's much faster to do a seperate query for the location_ids
        // than it is to use this as a subquery
        $sql    = "select location_id from locations where subunit_id is null and address_id=?";
        $query  = $this->pdo->prepare($sql);
        $query->execute([$address_id]);
        $result = $query->fetchAll(\PDO::FETCH_COLUMN);
        if ($result) {
            $location_ids = implode(',', $result);
            $locationsRepo = new PdoLocationsRepository($this->pdo);
            $select        = $locationsRepo->baseSelect();
            $select->where("l.location_id in ($location_ids)");

            $query = $this->pdo->prepare($select->getStatement());
            $query->execute($select->getBindValues());
            foreach ($query->fetchAll(\PDO::FETCH_ASSOC) as $row) {
                $locations[] = new Location($row);
            }
        }
        return $locations;
    }

    public function findPurposes(int $address_id): array
    {
        $sql = "select distinct p.*
                from locations         l
                join location_purposes x on l.location_id=x.location_id
                join purposes          p on p.id=x.purpose_id
                where l.subunit_id is null
                  and l.address_id=?";

        $query = $this->pdo->prepare($sql);
        $query->execute([$address_id]);
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @return array  An array of Place entities
     */
    public function findPlaces(int $address_id): array
    {
        $sql = "select p.id
                from locations    l
                join place.places p on p.address_location_id=l.location_id
                where l.subunit_id is null
                  and l.address_id=?";
        $query = $this->pdo->prepare($sql);
        $query->execute([$address_id]);

        $place_ids = $query->fetchAll(\PDO::FETCH_COLUMN);
        $places    = [];
        $repo      = new \Application\Places\PdoPlacesRepository($this->pdo);
        foreach ($place_ids as $id) { $places[] = $repo->load($id); }
        return $places;
    }

    /**
     * Alias for PdoSubunitsRepository::find()
     */
    public function findSubunits(array $fields, ?array $order=null, ?int $itemsPerPage=null, ?int $currentPage=null): array
    {
        $repo = new PdoSubunitsRepository($this->pdo);
        return $repo->find($fields, $order, $itemsPerPage, $currentPage);
    }

    public function changeLog(?int   $address_id  =null,
                              ?array $order       =null,
                              ?int   $itemsPerPage=null,
                              ?int   $currentPage =null): array
    {
        $logType = self::LOG_TYPE;
        $select  = $this->queryFactory->newSelect();
        $select->cols(["l.{$logType}_id as entity_id", "'{$logType}' as type",
                       'l.id', 'l.person_id', 'l.contact_id', 'l.action_date', 'l.action', 'l.notes',
                       'p.firstname as  person_firstname', 'p.lastname as  person_lastname',
                       'c.firstname as contact_firstname', 'c.lastname as contact_lastname',
                       "concat_ws(' ', a.street_number_prefix, a.street_number, a.street_number_suffix,
                                      sn.direction, sn.name, sn.post_direction, st.code) as entity"
                     ])
               ->from("{$logType}_change_log l")
               ->join('INNER', 'addresses            a',  'a.id = l.address_id')
               ->join('INNER', 'streets              s',  's.id = a.street_id')
               ->join('INNER', 'street_designations sd',  's.id =sd.street_id and sd.type_id='.Street::TYPE_STREET)
               ->join('INNER', 'street_names        sn', 'sn.id =sd.street_name_id')
               ->join('LEFT',  'street_types        st', 'st.id =sn.suffix_code_id')
               ->join('LEFT',  'people               p',  'p.id = l.person_id')
               ->join('LEFT',  'people               c',  'c.id = l.contact_id');
        if ($address_id) {
            $select->where("{$logType}_id=?", $address_id);
        }
        $select->orderBy(['l.action_date desc']);
        $result = parent::performSelect($select, $itemsPerPage, $currentPage);

        $changeLog = [];
        foreach ($result['rows'] as $row) {
            $changeLog[] = ChangeLogEntry::hydrate($row);
        }
        $result['rows'] = $changeLog;
        return $result;
    }

    /**
     * Check for any errors in an add request
     */
    public function validate(AddRequest $req): array
    {
        $errors = [];
        // Check not null fields
        if (!$req->street_number  ) { $errors[] = 'addresses/missingStreetNumber'; }
        if (!$req->address_type   ) { $errors[] = 'addresses/missingType';         }
        if (!$req->street_id      ) { $errors[] = 'addresses/missingStreet';       }
        if (!$req->jurisdiction_id) { $errors[] = 'addresses/missingJurisdiction'; }
        if (!$req->state          ) { $errors[] = 'addresses/missingState';        }
        if (!$req->zip            ) { $errors[] = 'addresses/missingZip';          }

        // Check foreign keys
        if (!parent::isValidId($req->street_id      , 'streets'      )) { $errors[] =       'streets/unknown'; }
        if (!parent::isValidId($req->jurisdiction_id, 'jurisdictions')) { $errors[] = 'jurisdictions/unknown'; }
        if (!parent::isValidId($req->user_id        , 'people'       )) { $errors[] =        'people/unknown'; }

        if ($req->township_id     && !parent::isValidId($req->township_id    , 'townships'     )) { $errors[] =     'townships/unknown'; }
        if ($req->subdivision_id  && !parent::isValidId($req->subdivision_id , 'subdivisions'  )) { $errors[] =  'subdivisions/unknown'; }
        if ($req->plat_id         && !parent::isValidId($req->plat_id        , 'plats'         )) { $errors[] =         'plats/unknown'; }
        if ($req->location_id     && !parent::isValidId($req->location_id, 'locations', 'location_id')) { $errors[] =     'locations/unknown'; }
        if ($req->locationType_id && !parent::isValidId($req->locationType_id, 'location_types')) { $errors[] = 'locationTypes/unknown'; }
        if ($req->contact_id      && !parent::isValidId($req->contact_id     , 'people'        )) { $errors[] =        'people/unknown'; }

        if (!$this->isValidZip($req->zip, $req->city)) { $errors[] = 'addresses/invalidZip'; }

        # The duplicate check depends on some of the required fields.
        # If they are missing, the duplicate check will return false positives.
        if ($errors) { return $errors; }

        $sql = "select count(*) from addresses
                where street_id            = ?
                  and street_number_prefix = ?
                  and street_number        = ?
                  and street_number_suffix = ?";
        $query = $this->pdo->prepare($sql);
        $query->execute([
            $req->street_id,
            $req->street_number_prefix,
            $req->street_number,
            $req->street_number_suffix
        ]);
        if ($query->fetchColumn()) { $errors[] = 'addresses/duplicateAddress'; }

        return $errors;
    }

    private function isValidZip(int $zip, string $city): bool
    {
        $sql = 'select id from zip_codes where zip=? and city=?';
        $query = $this->pdo->prepare($sql);
        $query->execute([$zip, $city]);
        return $query->fetchColumn() ? true : false;
    }

    //---------------------------------------------------------------
    // Write functions
    //---------------------------------------------------------------
    /**
     * Creates a new Address record
     *
     * @return int    The new address_id
     */
    public function add(AddRequest $req): int
    {
        // Prepare to save data for all address fields
        $data = [];
        foreach (self::$fieldmap as $f=>$map) {
            if ($map['prefix'] == 'a'
                && $f != 'id'
                && $req->$f) {

                $data[$map['dbName']] = $req->$f;
            }
        }

        $this->pdo->beginTransaction();

        $address_id = parent::saveToTable($data, self::TABLE);
        if ($address_id) {
            $location = new Location((array)$req);
            // This field is named differently in the AddRequest
            $location->type_id    = $req->locationType_id;
            $location->address_id = $address_id;

            try {
                $locationsRepo = new PdoLocationsRepository($this->pdo);
                $location_id   = $locationsRepo->assign($location);
                if (!$req->location_id) {
                    $locationsRepo->activateAddress($location_id, $address_id);
                }

                // Save address status
                $this->saveStatus         ($address_id,  $req->status,           self::LOG_TYPE);

                // Return the new address_id
                $this->pdo->commit();
                return $address_id;
            }
            catch (\Exception $e) {
                $this->pdo->rollBack();
                throw $e;
            }
        }
        $this->pdo->rollBack();
        throw new \Exception('databaseError');
    }

    /**
     * Makes an address the active one for a location.
     *
     * There should be only one active address per location.
     */
    public function activate(int $address_id, int $location_id)
    {
        $sql = "update locations set active=FALSE
                where location_id=? and subunit_id is null";

        $query = $this->pdo->prepare($sql);
        $query->execute([$location_id]);

        $sql = "update locations set active=TRUE
                where address_id=? and location_id=? and subunit_id is null";
        $query = $this->pdo->prepare($sql);
        $query->execute([$address_id, $location_id]);
    }


    public function correct(CorrectRequest $req)
    {
        $sql = "update addresses
                set street_id=?,
                    street_number_prefix=?,
                    street_number=?,
                    street_number_suffix=?,
                    zip=?,
                    zipplus4=?,
                    notes=?
                where id=?";
        $query = $this->pdo->prepare($sql);
        $query->execute([
            $req->street_id,
            $req->street_number_prefix,
            $req->street_number,
            $req->street_number_suffix,
            $req->zip,
            $req->zipplus4,
            $req->notes,
            $req->address_id
        ]);
    }

    public function update(UpdateRequest $req)
    {
        $sql = "update addresses
                set address2        =?,
                    address_type    =?,
                    jurisdiction_id =?,
                    township_id     =?,
                    subdivision_id  =?,
                    plat_id         =?,
                    section         =?,
                    quarter_section =?,
                    plat_lot_number =?,
                    notes           =?
                where id=?";
        $query = $this->pdo->prepare($sql);
        $query->execute([
            $req->address2,
            $req->address_type,
            $req->jurisdiction_id,
            $req->township_id,
            $req->subdivision_id,
            $req->plat_id,
            $req->section,
            $req->quarter_section,
            $req->plat_lot_number,
            $req->notes,
            $req->address_id
        ]);



        $sql = "update locations set type_id=?, mailable=?, occupiable=?, group_quarter=?
                where address_id=? and subunit_id is null and active";
        $query = $this->pdo->prepare($sql);
        $query->execute([
            $req->locationType_id,
            $req->mailable,
            $req->occupiable,
            $req->group_quarter,
            $req->address_id
        ]);
  }

    /**
     * @return int  The new address_id
     */
    public function readdress(ReaddressRequest $req): int
    {
        $old_address_id = $req->address_id;
        $old_address    = $this->load($old_address_id);

        // Retire the old address
        if ($old_address->status != ChangeLog::STATUS_RETIRED) {
            $this->saveStatus($old_address_id, ChangeLog::STATUS_RETIRED, self::LOG_TYPE);
        }

        // Create the new address at the same location
        $addRequest = new AddRequest($req->user_id, (array)$req);
        $addRequest->status = ChangeLog::STATUS_CURRENT;
        return $this->add($addRequest);
    }

    /**
     * Save changes to street numbers for a bunch of addresses
     */
    public function renumber(RenumberRequest $request)
    {
        $sql = 'update addresses
                set street_number_prefix=?,
                    street_number=?,
                    street_number_suffix=?
                where id=?';
        $query = $this->pdo->prepare($sql);
        foreach ($request->address_numbers as $a) {
            $query->execute([
                $a->street_number_prefix,
                $a->street_number,
                $a->street_number_suffix,
                $a->address_id
            ]);
        }
    }

    /**
     * Change the address_id for a subunit
     */
    public function moveSubunitsToAddress(int $old_address_id, int $new_address_id)
    {
        $sql = "update locations set address_id=?
                where address_id=? and subunit_id in
                (select id from subunits where address_id=?)";
        $query = $this->pdo->prepare($sql);
        $query->execute([$new_address_id, $old_address_id, $old_address_id]);

        $sql = "update subunits set address_id=? where address_id=?";
        $query = $this->pdo->prepare($sql);
        $query->execute([$new_address_id, $old_address_id]);
    }

    //---------------------------------------------------------------
    // Metadata Functions
    //---------------------------------------------------------------
    public function cities(): array
    {
        $sql = 'select distinct city from zip_codes order by city';
        $result = $this->pdo->query($sql);
        return $result->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function jurisdictions(): array
    {
        $sql = "select * from jurisdictions order by name";
        $result = $this->pdo->query($sql);
        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function quarterSections(): array
    {
        $sql = "select distinct quarter_section from addresses
                where quarter_section is not null
                order by quarter_section";
        $result = $this->pdo->query($sql);
        return $result->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function sections(): array
    {
        $sql = "select distinct section from addresses
                where section is not null
                order by section";
        $result = $this->pdo->query($sql);
        return $result->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function streetTypes(): array
    {
        $sql = "select * from street_types order by name";
        $result = $this->pdo->query($sql);
        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function subunitTypes(): array
    {
        $sql = "select * from subunit_types order by name";
        $result = $this->pdo->query($sql);
        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function townships(): array
    {
        $sql = 'select * from townships order by name';
        $result = $this->pdo->query($sql);
        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function types(): array
    {
        $sql    = "select unnest(enum_range(null::address_types))";
        $result = $this->pdo->query($sql);
        return $result->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function zipCodes(): array
    {
        $sql = "select * from zip_codes order by city, zip";
        $result = $this->pdo->query($sql);
        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function locationTypes(): array
    {
        $result = $this->pdo->query('select * from location_types order by name');
        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }
}
