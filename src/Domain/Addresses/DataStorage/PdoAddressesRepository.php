<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Addresses\DataStorage;

use Aura\SqlQuery\Common\SelectInterface;
use Domain\PdoRepository;
use Domain\Addresses\Entities\Address;
use Domain\Addresses\UseCases\Add\AddRequest;
use Domain\Addresses\UseCases\Correct\CorrectRequest;
use Domain\Addresses\UseCases\Renumber\RenumberRequest;
use Domain\Addresses\UseCases\Search\SearchRequest;

use Domain\Logs\Entities\ChangeLogEntry;
use Domain\Logs\Metadata as ChangeLog;

class PdoAddressesRepository extends PdoRepository implements AddressesRepository
{
    use \Domain\Logs\DataStorage\ChangeLogTrait;
    use \Domain\Logs\DataStorage\StatusLogTrait;
    protected $logType = 'address';

    const TYPE_STREET = 1;
    const TABLE = 'addresses';

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

        'status' => ['prefix'=>'status', 'dbName'=>'status']
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
               ->join('LEFT', 'street_designations sd', 's.id=sd.street_id and sd.type_id='.self::TYPE_STREET)
               ->join('LEFT', 'street_names        sn', 'sd.street_name_id=sn.id')
               ->join('LEFT', 'street_types        st', 'sn.suffix_code_id=st.id')
               ->join('LEFT', 'address_status  status', 'a.id=status.address_id and status.start_date <= now() and (status.end_date is null or status.end_date >= now())');

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
                else {
                    if ($f == 'location_id') {
                        $select->distinct();
                        $select->join('INNER', 'locations l', 'l.address_id=a.id');
                        $select->where('l.location_id=?', $v);
                    }
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
        $current = ChangeLog::STATUS_CURRENT;
        $select  = $this->baseSelect();
        $cols    = $this->columns();
        $cols[]  = "( select count(*)
                      from subunits       x
                      join subunit_status xs on x.id=xs.subunit_id and xs.start_date <= now() and (xs.end_date is null or xs.end_date >= now()) and xs.status='$current'
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
                            $select->where("lower($column) like ?", strtolower("$v%"));
                        break;

                        default:
                            $select->where("$column=?", $v);
                    }
                }
                else {
                    if ($f == 'location_id') {
                        $select->join('INNER', 'locations l', 'l.address_id=a.id and l.subunit_id is null');
                        $select->where('l.location_id=?', $v);
                    }
                }
            }
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
     * Load location objects for an address
     */
    public function locations(int $address_id): array
    {
        $output = [];
        $locationRepo = new \Domain\Locations\DataStorage\PdoLocationsRepository($this->pdo);
        $subunitRepo  = new \Domain\Subunits\DataStorage\PdoSubunitsRepository($this->pdo);

        $select = $locationRepo->baseSelect();
        $select->where('l.address_id=?', $address_id);
        $select->where('l.subunit_id is null');

        $query = $this->pdo->prepare($select->getStatement());
        $query->execute($select->getBindValues());
        foreach ($query->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            $addresses =        $this->find(['location_id'=>$row['location_id']]);
            $subunits  = $subunitRepo->find(['location_id'=>$row['location_id']]);

            $location = new \Domain\Locations\Entities\Location($row);
            $location->addresses = $addresses['rows'];
            $location->subunits  = $subunits ['rows'];
            $output[] = $location;
        }
        return $output;
    }

    /**
     * Load subunit objects for an address
     */
    public function subunits(int $address_id): array
    {
        $subunits = [];
        $repo = new \Domain\Subunits\DataStorage\PdoSubunitsRepository($this->pdo);
        $select = $repo->baseSelect();
        $select->where('s.address_id=?', $address_id);

        $query = $this->pdo->prepare($select->getStatement());
        $query->execute($select->getBindValues());
        foreach ($query->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            $subunits[] = new \Domain\Subunits\Entities\Subunit($row);
        }
        return $subunits;
    }

    public function changeLog(?int   $address_id  =null,
                              ?array $order       =null,
                              ?int   $itemsPerPage=null,
                              ?int   $currentPage =null): array
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(["l.{$this->logType}_id as entity_id", "'{$this->logType}' as type",
                       'l.id', 'l.person_id', 'l.contact_id', 'l.action_date', 'l.action', 'l.notes',
                       'p.firstname as  person_firstname', 'p.lastname as  person_lastname',
                       'c.firstname as contact_firstname', 'c.lastname as contact_lastname',
                       "concat_ws(' ', a.street_number_prefix, a.street_number, a.street_number_suffix,
                                      sn.direction, sn.name, sn.post_direction, st.code) as entity"
                     ])
               ->from("{$this->logType}_change_log l")
               ->join('INNER', 'addresses            a',  'a.id = l.address_id')
               ->join('INNER', 'streets              s',  's.id = a.street_id')
               ->join('INNER', 'street_designations sd',  's.id =sd.street_id and sd.type_id='.self::TYPE_STREET)
               ->join('INNER', 'street_names        sn', 'sn.id =sd.street_name_id')
               ->join('INNER', 'street_types        st', 'st.id =sn.suffix_code_id')
               ->join('LEFT',  'people               p',  'p.id = l.person_id')
               ->join('LEFT',  'people               c',  'c.id = l.contact_id');
        if ($address_id) {
            $select->where("{$this->logType}_id=?", $address_id);
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
            if ($req->location_id) {
                // Create a new row in locations by copying data from
                // the active row for the location_id.
                // The new row should not be active.
                $sql = 'select * from locations where location_id=? order by active desc';
                $result = parent::doQuery($sql, [$req->location_id]);
                if (!count($result)) {
                    $this->pdo->rollBack();
                    throw new \Exception('locations/unknown');
                }

                $location = $result[0];
                $location['address_id'] = $address_id;
                // Boolean fields have to be converted to explicit true/false
                $location['active'    ] = 'false';
                $location['mailable'  ] = $location['mailable'  ] ? 'true' : 'false';
                $location['occupiable'] = $location['occupiable'] ? 'true' : 'false';

                $insert  = $this->queryFactory->newInsert();
                $insert->into('locations')->cols($location);
                $sql     = $insert->getStatement();
                $query   = $this->pdo->prepare($sql);
                $success = $query->execute($insert->getBindValues());
                if (!$success) {
                    $this->pdo->rollBack();
                    throw new \Exception('databaseError');
                }
            }
            else {
                // Create a new row in locations using data from the request.
                $insert = $this->queryFactory->newInsert();
                $insert->into('locations')->cols([
                    'address_id'   => $address_id,
                    'type_id'      => $req->locationType_id,
                    'mailable'     => $req->mailable,
                    'occupiable'   => $req->occupiable,
                    'active'       => 'true',
                    'trash_day'    => $req->trash_day,
                    'recycle_week' => $req->recycle_week
                ]);
                $query   = $this->pdo->prepare($insert->getStatement());
                $success = $query->execute($insert->getBindValues());
                if (!$success) {
                    $this->pdo->rollBack();
                    throw new \Exception('databaseError');
                }

                $location_id = (int)$this->pdo->lastInsertId('locations_location_id_seq');

                // Save a new location status using the request status
                $this->saveLocationStatus($location_id, $req->status);
            }

            // Save address status
            $this->saveStatus($address_id, $req->status);

            // Return the new address_id
            $this->pdo->commit();
            return $address_id;
        }
        $this->pdo->rollBack();
        throw new \Exception('databaseError');
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


    public function saveLocationStatus(int $location_id, string $status)
    {
        $repo = new \Domain\Locations\DataStorage\PdoLocationsRepository($this->pdo);
        $repo->saveStatus($location_id, $status);
    }

    //---------------------------------------------------------------
    // Metadata Functions
    //---------------------------------------------------------------
    public function cities(): array
    {
        return parent::distinctFromTable('city', self::TABLE);
    }

    public function jurisdictions(): array
    {
        return $this->doQuery('select * from jurisdictions order by name');
    }

    public function quarterSections(): array
    {
        return parent::distinctFromTable('quarter_section', self::TABLE);
    }

    public function sections(): array
    {
        return parent::distinctFromTable('section', self::TABLE);
    }

    public function streetTypes(): array
    {
        return $this->doQuery('select * from street_types order by name');
    }

    public function subunitTypes(): array
    {
        return $this->doQuery('select * from subunit_types order by name');
    }

    public function townships(): array
    {
        return $this->doQuery('select * from townships order by name');
    }

    public function types(): array
    {
        return $this->distinctFromTable('address_type', self::TABLE);
    }

    public function zipCodes(): array
    {
        return parent::distinctFromTable('zip', self::TABLE);
    }
}
