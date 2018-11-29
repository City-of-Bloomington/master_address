<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Subunits\DataStorage;

use Aura\SqlQuery\Common\SelectInterface;

use Domain\PdoRepository;

use Domain\Locations\Entities\Location;
use Domain\Subunits\Entities\Subunit;
use Domain\Subunits\UseCases\Add\AddRequest;
use Domain\Subunits\UseCases\Correct\CorrectRequest;

use Domain\Logs\Entities\ChangeLogEntry;
use Domain\Logs\Metadata as ChangeLog;

class PdoSubunitsRepository extends PdoRepository implements SubunitsRepository
{
    use \Domain\Logs\DataStorage\ChangeLogTrait;
    use \Domain\Logs\DataStorage\StatusLogTrait;

    const LOG_TYPE    = 'subunit';
    const TABLE       = 'subunits';
    const TYPE_STREET = 1;

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
        'type_name'     => ['prefix'=>'t', 'dbName' => 'name'         ]
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
               ->join('LEFT', 'subunit_status x', 's.id=x.subunit_id and x.start_date <= now() and (x.end_date is null or x.end_date >= now())');

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

    public function locations(int $subunit_id): array
    {
        $output = [];
        $addressRepo  = new \Domain\Addresses\DataStorage\PdoAddressesRepository($this->pdo);

        $sql   = "select * from locations where subunit_id=?";
        $query = $this->pdo->prepare($sql);
        $query->execute([$subunit_id]);
        foreach ($query->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            $subunits  =        $this->find(['location_id'=>$row['location_id']]);
            $addresses = $addressRepo->find(['location_id'=>$row['location_id']]);

            $location = new \Domain\Locations\Entities\Location($row);
            $location->subunits  = $subunits['rows'];
            $location->addresses = $addresses['rows'];
            $output[] = $location;
        }
        return $output;
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
               ->join('INNER', 'street_designations sd',  's.id =sd.street_id and sd.type_id='.self::TYPE_STREET)
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
        $data = [];
        foreach (self::$fieldmap as $f=>$map) {
            if ($map['prefix'] == 's' && $f != 'id' && $req->$f) {
                $data[$map['dbName']] = $req->$f;
            }
        }

        $subunit_id = parent::saveToTable($data, self::TABLE);
        if ($subunit_id) {
            // Set the subunit status
            $this->saveStatus($subunit_id, $req->status, self::LOG_TYPE);

            // Save the location
            // Create a new row in locations using data from the request.
            $insert = $this->queryFactory->newInsert();
            $insert->into('locations')->cols([
                'address_id'   => $req->address_id,
                'subunit_id'   => $subunit_id,
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

            // Set the location status
            $this->saveStatus($location_id, $req->status, 'location');

            $this->pdo->commit();
            return $subunit_id;
        }
        $this->pdo->rollBack();
        throw new \Exception('databaseError');
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
