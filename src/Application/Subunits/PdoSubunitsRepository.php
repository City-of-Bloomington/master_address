<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Subunits;
use Domain\Subunits\DataStorage\SubunitsRepository;

use Aura\SqlQuery\Common\SelectInterface;

use Application\PdoRepository;
use Application\Addresses\PdoAddressesRepository;
use Application\Locations\PdoLocationsRepository;

use Domain\Addresses\Entities\Address;
use Domain\Locations\Entities\Location;
use Domain\Streets\Metadata as Street;
use Domain\Subunits\Entities\Subunit;
use Domain\Subunits\UseCases\Add\AddRequest;
use Domain\Subunits\UseCases\Correct\CorrectRequest;
use Domain\Subunits\UseCases\Update\Request as UpdateRequest;

use Domain\Logs\Entities\ChangeLogEntry;

class PdoSubunitsRepository extends PdoRepository implements SubunitsRepository
{
    use \Domain\Logs\DataStorage\ChangeLogTrait;
    use \Domain\Logs\DataStorage\StatusLogTrait;

    const LOG_TYPE    = 'subunit';
    const TABLE       = 'subunits';

    public static $DEFAULT_SORT = [
        'type_id',
        'identifier'
    ];

    /**
     * Maps response fieldnames to the names used in the database
     *
     * subunit_field => [database_info]
     */
    public static $fieldmap = [
        'id'            => ['prefix'=>'s', 'dbName' => 'id'           ],
        'address_id'    => ['prefix'=>'s', 'dbName' => 'address_id'   ],
        'type_id'       => ['prefix'=>'s', 'dbName' => 'type_id'      ],
        'identifier'    => ['prefix'=>'s', 'dbName' => 'identifier'   ],
        'notes'         => ['prefix'=>'s', 'dbName' => 'notes'        ],
        'state_plane_x' => ['prefix'=>'s', 'dbName' => 'state_plane_x'],
        'state_plane_y' => ['prefix'=>'s', 'dbName' => 'state_plane_y'],
        'latitude'      => ['prefix'=>'s', 'dbName' => 'latitude'     ],
        'longitude'     => ['prefix'=>'s', 'dbName' => 'longitude'    ],
        'usng'          => ['prefix'=>'s', 'dbName' => 'usng'         ],
        'status'        => ['prefix'=>'x', 'dbName' => 'status'       ],
        'type_code'     => ['prefix'=>'t', 'dbName' => 'code'         ],
        'type_name'     => ['prefix'=>'t', 'dbName' => 'name'         ],
        'location_id'   => ['prefix'=>'l', 'dbName' => 'location_id'  ]
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

    public function baseSelect(): SelectInterface
    {
        $select = $this->queryFactory->newSelect();
        $select->cols($this->columns())
               ->from(self::TABLE.' s')
               ->join('LEFT', 'subunit_types  t', 's.type_id=t.id')
               ->join('LEFT', 'locations      l', 's.id=l.subunit_id and l.active')
               ->joinSubSelect('LEFT', 'select distinct on (subunit_id) subunit_id, status from subunit_status order by subunit_id, start_date desc', 'x', 's.id=x.subunit_id');

        return $select;
    }

    public function load(int $subunit_id): Subunit
    {
        $select = $this->baseSelect();
        $select->where('s.id=?', $subunit_id);

        $result = $this->performSelect($select);
        if (count($result['rows'])) {
            return new Subunit($result['rows'][0]);
        }
        throw new \Exception('subunits/unknown');
    }

    /**
     * Alias for PdoAddressesRepository::load()
     */
    public function loadAddress(int $address_id): Address
    {
        $addressRepo = new PdoAddressesRepository($this->pdo);
        return $addressRepo->load($address_id);
    }

    /**
     * Return all location rows for any location_id on this subunit
     *
     * Locations can have multiple address and addresses can have multiple
     * locations.  We need to be able to know all the other addresses
     * that are related to an address via the locations.
     *
     * This function returns all related location rows, not just the
     * location rows with this subunit_id.
     *
     * The return array should match the data returned from LocationsRepository::find()
     *
     * @return array   An array of Location entities
     */
    public function findLocations(int $subunit_id): array
    {
        $locations     = [];
        $locationsRepo = new PdoLocationsRepository($this->pdo);
        $select        = $locationsRepo->baseSelect();
        $select->where("l.location_id in (
                            select location_id from locations
                            where subunit_id=?)", $subunit_id);
        $query = $this->pdo->prepare($select->getStatement());
        $query->execute($select->getBindValues());
        foreach ($query->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            $locations[] = new Location($row);
        }
        return $locations;
    }

    /**
     * Alias for PdoAddressesRepository::find()
     */
    public function findAddresses(array $fields, ?array $order=null, ?int $itemsPerPage=null, ?int $currentPage=null): array
    {
        $addressRepo = new PdoAddressesRepository($this->pdo);
        return $addressRepo->find($fields, $order, $itemsPerPage, $currentPage);
    }

    /**
     * Exact matching of field values
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
                        $select->join('INNER', 'locations l', 'l.subunit_id=s.id');
                        $select->where('l.location_id=?', $v);
                    }
                }
            }
        }
        return $this->doSelect($select, $order, $itemsPerPage, $currentPage);
    }

    /**
     * Returns an array of ChangeLog entries
     */
    public function changeLog(?int   $subunit_id  =null,
                              ?array $order       =null,
                              ?int   $itemsPerPage=null,
                              ?int   $currentPage =null): array
    {
        $logType = self::LOG_TYPE;
        $select = $this->queryFactory->newSelect();
        $select->cols(["l.{$logType}_id as entity_id", "'{$logType}' as type",
                       'l.id', 'l.person_id', 'l.contact_id', 'l.action_date', 'l.action', 'l.notes',
                       'p.firstname as  person_firstname', 'p.lastname as  person_lastname',
                       'c.firstname as contact_firstname', 'c.lastname as contact_lastname',
                       "concat_ws(' ', a.street_number_prefix, a.street_number, a.street_number_suffix,
                                      sn.direction, sn.name, sn.post_direction, st.code,
                                       u.identifier, ut.code) as entity"])
               ->from("{$logType}_change_log l")
               ->join('INNER', 'subunits             u',  'u.id = l.subunit_id')
               ->join('INNER', 'subunit_types       ut', 'ut.id = u.type_id')
               ->join('INNER', 'addresses            a',  'a.id = u.address_id')
               ->join('INNER', 'streets              s',  's.id = a.street_id')
               ->join('INNER', 'street_designations sd',  's.id =sd.street_id and sd.type_id='.Street::TYPE_STREET)
               ->join('INNER', 'street_names        sn', 'sn.id =sd.street_name_id')
               ->join('INNER', 'street_types        st', 'st.id =sn.suffix_code_id')
               ->join('LEFT',  'people               p',  'p.id = l.person_id')
               ->join('LEFT',  'people               c',  'c.id = l.contact_id');
        if ($subunit_id) {
            $select->where("{$logType}_id=?", $subunit_id);
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

    private function doSelect(SelectInterface $select, ?array $order=null, ?int $itemsPerPage=null, ?int $currentPage=null): array
    {
        $select->orderBy(self::$DEFAULT_SORT);
        $result = parent::performSelect($select, $itemsPerPage, $currentPage);

        $subunits = [];
        foreach ($result['rows'] as $r) { $subunits[] = new Subunit($r); }
        $result['rows'] = $subunits;
        return $result;
    }
    //---------------------------------------------------------------
    // Write Functions
    //---------------------------------------------------------------
    /**
     * @return int     The new subunit_id
     */
    public function add(AddRequest $req): int
    {
        $this->pdo->beginTransaction();

        // Save the subunit
        // Prepare to save data for all subunit fields
        $data = [
            'address_id'    => $req->address_id,
            'type_id'       => $req->type_id,
            'identifier'    => $req->identifier,
            'notes'         => $req->notes
        ];
        $subunit_id = parent::saveToTable($data, self::TABLE);
        if ($subunit_id) {
            $location = new Location((array)$req);
            $location->type_id    = $req->locationType_id;
            $location->subunit_id = $subunit_id;

            try {
                $locationsRepo = new PdoLocationsRepository($this->pdo);
                $location_id   = $locationsRepo->assign($location);
                $locationsRepo->activateSubunit($location_id, $subunit_id);

                // Set the subunit status
                         $this->saveStatus($subunit_id,  $req->status,           self::LOG_TYPE);
                $locationsRepo->saveStatus($location_id, $req->status, $locationsRepo::LOG_TYPE);

                $this->pdo->commit();
                return $subunit_id;
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
     * Flags a subunit as the active one for a location.
     *
     * There should only be one active subunit per location
     */
    public function activate(int $subunit_id, int $location_id)
    {
        $sql   = "update locations set active=FALSE
                  where location_id=? and subunit_id is not null";
        $query = $this->pdo->prepare($sql);
        $query->execute([$location_id]);

        $sql   = "update locations set active=TRUE
                  where location_id=? and subunit_id=?";
        $query = $this->pdo->prepare($sql);
        $query->execute([$location_id, $subunit_id]);
    }

    public function correct(CorrectRequest $req)
    {
        $sql = "update subunits
                set type_id=?, identifier=?, notes=?
                where id=?";
        $query = $this->pdo->prepare($sql);
        $query->execute([
            $req->type_id, $req->identifier, $req->notes,
            $req->subunit_id
        ]);
    }

    public function update(UpdateRequest $req)
    {
        $sql = "update subunits set notes=? where id=?";
        $query = $this->pdo->prepare($sql);
        $query->execute([$req->notes, $req->subunit_id]);

        $sql = "update locations set mailable=?, occupiable=?, group_quarter=?
                where subunit_id=? and active";
        $query = $this->pdo->prepare($sql);
        $query->execute([
            $req->mailable,
            $req->occupiable,
            $req->group_quarter,
            $req->subunit_id
        ]);
    }

    //---------------------------------------------------------------
    // Metadata Functions
    //---------------------------------------------------------------
    public function types(): array
    {
        $result = $this->pdo->query('select * from subunit_types order by name');
        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function locationTypes(): array
    {
        $result = $this->pdo->query('select * from location_types order by name');
        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }
}
