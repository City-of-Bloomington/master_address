<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Locations\DataStorage;

use Aura\SqlQuery\Common\SelectInterface;
use Domain\PdoRepository;

use Domain\Locations\Entities\Location;
use Domain\Locations\UseCases\Search\SearchRequest;

class PdoLocationsRepository extends PdoRepository implements LocationsRepository
{
    use \Domain\Logs\DataStorage\StatusLogTrait;
    protected $logType     = 'location';

    const TABLE = 'locations';

    /**
     * Maps response fieldnames to the names used in the database
     */
    public static $fieldmap = [
        'location_id'  => ['prefix'=>'l', 'dbName' => 'location_id' ],
        'type_id'      => ['prefix'=>'l', 'dbName' => 'type_id'     ],
        'address_id'   => ['prefix'=>'l', 'dbName' => 'address_id'  ],
        'subunit_id'   => ['prefix'=>'l', 'dbName' => 'subunit_id'  ],
        'mailable'     => ['prefix'=>'l', 'dbName' => 'mailable'    ],
        'occupiable'   => ['prefix'=>'l', 'dbName' => 'occupiable'  ],
        'active'       => ['prefix'=>'l', 'dbName' => 'active'      ],
        'trash_day'    => ['prefix'=>'l', 'dbName' => 'trash_day'   ],
        'recycle_week' => ['prefix'=>'l', 'dbName' => 'recycle_week'],
        'status'       => ['prefix'=>'x', 'dbName' => 'status'      ],
        'type_code'    => ['prefix'=>'t', 'dbName' => 'code'        ],
        'type_name'    => ['prefix'=>'t', 'dbName' => 'name'        ]
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
               ->from(self::TABLE.' l')
               ->join('LEFT', 'location_types  t', 'l.type_id=t.id')
               ->join('LEFT', 'location_status x', 'l.location_id=x.location_id and x.start_date <= now() and (x.end_date is null or x.end_date >= now())');

        return $select;
    }

    public function load(int $location_id): Location
    {
        $select = $this->baseSelect();
        $select->where('l.location_id=?', $location_id);
        $result = $this->performSelect($select);
        if (count($result['rows'])) {
            return new Location($result['rows'][0]);
        }
        throw new \Exception('locations/unknown');
    }

    /**
     * Look up locations using exact matching
     *
     * @return array   An array of Location entities
     */
    public function find(SearchRequest $req): array
    {
        $select = $this->baseSelect();
        foreach (get_class_vars('\Domain\Locations\Entities\Location') as $f=>$v) {
            if (!empty($req->$f)) {
                $column = self::$fieldmap[$f]['prefix'].'.'.self::$fieldmap[$f]['dbName'];
                $select->where("$column=?", $req->$f);
            }
        }
        $select->orderBy(self::$DEFAULT_SORT);
        $result = $this->performSelect($select, $req->itemsPerPage, $req->currentPage);

        $locations = [];
        foreach ($result['rows'] as $r) { $locations[] = new Location($r); }
        $result['rows'] = $locations;
        return $result;
    }

    //---------------------------------------------------------------
    // Write Functions
    //---------------------------------------------------------------
    public function save(Location $location): int
    {
        $data = [];
        foreach (self::$fieldmap as $f=>$db) {
            if ($db['prefix'] == 'l') {
                $data[$db['dbName']] = $location->$f;
            }
        }
        return parent::saveToTable($data, self::TABLE);
    }

    //---------------------------------------------------------------
    // Metadata functions
    //---------------------------------------------------------------
    public function types(): array
    {
        return parent::doQuery('select * from location_types');
    }

    public function trashDays(): array
    {
        return parent::distinctFromTable('trash_day', self::TABLE);
    }

    public function recycleWeeks(): array
    {
        return parent::distinctFromTable('recycle_week', self::TABLE);
    }
}
