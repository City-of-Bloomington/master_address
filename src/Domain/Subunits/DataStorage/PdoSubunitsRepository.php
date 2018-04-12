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
    protected $logType = 'subunit';

    const TABLE = 'subunits';
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
        $locationRepo = new \Domain\Locations\DataStorage\PdoLocationsRepository($this->pdo);
        $addressRepo  = new \Domain\Addresses\DataStorage\PdoAddressesRepository($this->pdo);

        $select = $locationRepo->baseSelect();
        $select->where('l.subunit_id=?', $subunit_id);

        $query = $this->pdo->prepare($select->getStatement());
        $query->execute($select->getBindValues());
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
    public function save(Subunit $subunit): int
    {
        $data = [];
        foreach (self::$fieldmap as $f=>$db) {
            // Only save the subunits table fields
            if ($db['prefix'] == 's') {
                $data[$db['dbName']] = $subunit->$f;
            }
        }
        return parent::saveToTable($data, self::TABLE);
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

    public function saveLocation(Location $location): int
    {
        $repo = new \Domain\Locations\DataStorage\PdoLocationsRepository($this->pdo);
        return $repo->save($location);
    }

    public function saveLocationStatus(int $location_id, string $status)
    {
        $repo = new \Domain\Locations\DataStorage\PdoLocationsRepository($this->pdo);
        $repo->saveStatus($location_id, $status);
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
