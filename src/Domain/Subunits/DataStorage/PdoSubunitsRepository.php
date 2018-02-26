<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Subunits\DataStorage;

use Aura\SqlQuery\Common\SelectInterface;

use Domain\PdoRepository;
use Domain\Subunits\Entities\Subunit;
use Domain\Subunits\Entities\Location;

use Domain\Logs\Entities\ChangeLogEntry;
use Domain\Logs\Metadata as ChangeLog;

class PdoSubunitsRepository extends PdoRepository implements SubunitsRepository
{
    use \Domain\Logs\DataStorage\ChangeLogTrait;
    use \Domain\Logs\DataStorage\StatusLogTrait;
    protected $logType = 'subunit';

    protected $tablename   = 'subunits';
    protected $entityClass = '\Domain\Subunits\Entities\Subunit';

    /**
     * Maps response fieldnames to the names used in the database
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
               ->from("{$this->tablename}     s")
               ->join('LEFT', 'subunit_types  t', 's.type_id=t.id')
               ->join('LEFT', 'subunit_status x', 's.id=x.subunit_id and x.start_date <= CURRENT_DATE and (x.end_date is null or x.end_date >= CURRENT_DATE)');

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
        $locations = [];
        $repo = new \Domain\Locations\DataStorage\PdoLocationsRepository($this->pdo);
        $select = $repo->baseSelect();
        $select->where('l.subunit_id=?', $subunit_id);

        $query = $this->pdo->prepare($select->getStatement());
        $query->execute($select->getBindValues());
        foreach ($query->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            $locations[] = new \Domain\Locations\Entities\Location($row);
        }
        return $locations;
    }

    public function saveLocationStatus(int $location_id, string $status)
    {
        $repo = new \Domain\Locations\DataStorage\PdoLocationsRepository($this->pdo);
        $repo->saveStatus($location_id, $status);
    }
}
