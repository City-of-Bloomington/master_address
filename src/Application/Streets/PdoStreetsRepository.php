<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Streets;
use Domain\Streets\DataStorage\StreetsRepository;

use Aura\SqlQuery\Common\SelectInterface;
use Application\PdoRepository;
use Domain\Logs\Entities\ChangeLogEntry;
use Domain\Streets\Entities\Designation;
use Domain\Streets\Entities\Intersection;
use Domain\Streets\Entities\Street;
use Domain\Streets\UseCases\Add\AddRequest;
use Domain\Streets\UseCases\Alias\AliasRequest;
use Domain\Streets\UseCases\Search\SearchRequest;
use Domain\Streets\UseCases\Update\UpdateRequest;

class PdoStreetsRepository extends PdoRepository implements StreetsRepository
{
    use \Domain\Logs\DataStorage\ChangeLogTrait;

    const TABLE       = 'streets';
    const LOG_TYPE    = 'street';
    const TYPE_STREET = 1;

    public static $DEFAULT_SORT = ['n.name'];

    /**
     * Maps response fieldnames to the names used in the database
     */
    public static $fieldmap = [
        'id'             => ['prefix'=>'s',    'dbName'=>'id'            ],
        'status'         => ['prefix'=>'s',    'dbName'=>'status'        ],
        'notes'          => ['prefix'=>'s',    'dbName'=>'notes'         ],
        'town_id'        => ['prefix'=>'s',    'dbName'=>'town_id'       ],
        'town_name'      => ['prefix'=>'town', 'dbName'=>'name'          ],
        'town_code'      => ['prefix'=>'town', 'dbName'=>'code'          ],
        'name_id'        => ['prefix'=>'n',    'dbName'=>'id'            ],
        'direction'      => ['prefix'=>'n',    'dbName'=>'direction'     ],
        'name'           => ['prefix'=>'n',    'dbName'=>'name'          ],
        'post_direction' => ['prefix'=>'n',    'dbName'=>'post_direction'],
        'suffix_code'    => ['prefix'=>'t',    'dbName'=>'code'          ],
        'type_id'        => ['prefix'=>'d',    'dbName'=>'type_id'       ],
        'type_name'      => ['prefix'=>'dt',   'dbName'=>'name'          ]
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
               ->from(self::TABLE.' s')
               ->join('LEFT', 'towns                   town', 's.town_id=town.id')
               ->join('INNER', 'street_designations       d',  's.id = d.street_id')
               ->join('INNER', 'street_names              n',  'n.id = d.street_name_id')
               ->join( 'LEFT', 'street_types              t',  't.id = n.suffix_code_id')
               ->join('INNER', 'street_designation_types dt', 'dt.id = d.type_id');
        return $select;
    }

    /**
     * Create a Street object from an array of data
     */
    private static function hydrateStreet(array $row): Street
    {
        return new Street($row);
    }

    /**
     * Load a street object from the database
     *
     * A street might have several names. We want to load the best name for
     * the street.  A current street should have designation type of STREET.
     * Retired streets might not have a STREET designation.
     *
     * STREET designation type = 1, so we order by designation type and take
     * the first result
     */
    public function load(int $street_id): Street
    {
        $select = $this->baseSelect();
        $select->where('s.id=?', $street_id);
        $select->orderBy(['d.type_id']);

        $result = $this->performSelect($select);
        if (count($result['rows'])) {
            return self::hydrateStreet($result['rows'][0]);
        }
        throw new \Exception('streets/unknown');
    }

    /**
     * Find streets in the database using loose matching
     *
     * @return array  An array of street objects
     */
    public function search(SearchRequest $req): array
    {
        $select = $this->baseSelect();
        foreach (self::$fieldmap as $f=>$m) {
            if (!empty($req->$f)) {
                $column = "$m[prefix].$m[dbName]";
                switch ($f) {
                    case 'name':
                        $select->where("lower($column) like ?", strtolower("{$req->$f}%"));
                    break;

                    default:
                        $select->where("$column=?", $req->$f);
                }
            }
        }
        $select->orderBy(self::$DEFAULT_SORT);
        $result = $this->performSelect($select, $req->itemsPerPage, $req->currentPage);

        $streets = [];
        foreach ($result['rows'] as $r) { $streets[] = self::hydrateStreet($r); }
        $result['rows'] = $streets;
        return $result;
    }

    public function changeLog(?int   $street_id   =null,
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
                       "concat_ws(' ', sn.direction, sn.name, sn.post_direction, st.code) as entity"
                     ])
               ->from("{$logType}_change_log l")
               ->join('INNER', 'streets              s',  's.id = l.street_id')
               ->join('INNER', 'street_designations sd',  's.id =sd.street_id and sd.type_id='.self::TYPE_STREET)
               ->join('INNER', 'street_names        sn', 'sn.id =sd.street_name_id')
               ->join('LEFT',  'street_types        st', 'st.id =sn.suffix_code_id')
               ->join('LEFT',  'people               p',  'p.id = l.person_id')
               ->join('LEFT',  'people               c',  'c.id = l.contact_id');
        if ($street_id) {
            $select->where("l.{$logType}_id=?", $street_id);
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
     * Saves a new street to the database and returns the ID for the street
     *
     * @return int  The new street_id
     */
    public function add(AddRequest $req): int
    {
        $this->pdo->beginTransaction();

        $street_id = parent::saveToTable([
            'town_id' => $req->town_id,
            'status'  => $req->status,
            'notes'   => $req->notes
        ], self::TABLE);

        if ($street_id) {
            $designation = new Designation([
                'street_id'  => $street_id,
                'start_date' => $req->start_date,
                'name_id'    => $req->name_id,
                'type_id'    => self::TYPE_STREET,
                'rank'       => 1
            ]);
            $designation_id = $this->addDesignation($designation);
            if ($designation_id) {
                $this->pdo->commit();
                return $street_id;
            }
        }
        $this->pdo->rollBack();
        throw new \Exception('databaseError');
    }

    /**
     * Save a street back to the database
     */
    public function update(UpdateRequest $req)
    {
        $sql = 'update streets set town_id=?, notes=? where id=?';
        $query = $this->pdo->prepare($sql);
        $query->execute([$req->town_id, $req->notes, $req->street_id]);
    }

    /**
     * Set the latest status for a street
     */
    public function saveStatus(int $street_id, string $status)
    {
        $sql = 'update streets set status=? where id=?';
        $query = $this->pdo->prepare($sql);
        $query->execute([$status, $street_id]);
    }

    /**
     * Save a new street designation to the database
     *
     * @return int  The new designation_id
     */
    public function addDesignation(Designation $d): int
    {
        return parent::saveToTable([
            'street_id'      => $d->street_id,
            'street_name_id' => $d->name_id,
            'type_id'        => $d->type_id,
            'rank'           => $d->rank,
            'start_date'     => $d->start_date->format('c')
        ], 'street_designations');
    }

    public function nextDesignationRank(int $street_id): int
    {
        $sql   = 'select max(rank)+1 from street_designations where street_id=?';
        $query = $this->pdo->prepare($sql);
        $query->execute([$street_id]);
        return $query->fetchColumn();
    }

    private function designationSelect(): SelectInterface
    {
        $columns = ['d.id',
                    'd.street_id',
                    'd.street_name_id as name_id',
                    'd.type_id',
                    'd.start_date',
                    'd.rank',
                    'dt.name as type',
                    'n.direction',
                    'n.name',
                    'n.post_direction',
                    't.code as suffix_code'];
        $select = $this->queryFactory->newSelect();
        $select->cols($columns)
               ->from('street_designations d')
               ->join('INNER', 'street_designation_types dt', 'dt.id=d.type_id')
               ->join('INNER', 'street_names              n',  'n.id=d.street_name_id')
               ->join('LEFT' , 'street_types              t',  't.id=n.suffix_code_id');
        return $select;
    }

    /**
     * Query for street designations using exact matching
     */
    public function findDesignations(array $fields, ?array $sort=null): array
    {
        $designations = [];

        $select = $this->designationSelect();
        foreach ($fields as $f=>$v) {
            switch ($f) {
                case 'street_id':
                case 'street_name_id':
                case 'type_id':
                    $select->where("d.$f=?", $v);
                break;
            }
        }
        $select->orderBy($sort ?? ['d.street_id', 'd.rank']);
        $result = $this->performSelect($select);
        foreach ($result['rows'] as $r) { $designations[] = Designation::hydrate($r); }
        return $designations;
    }

    /**
     * Load a street designation object from the database
     */
    public function loadDesignation(int $designation_id): Designation
    {
        $select = $this->designationSelect();
        $select->where('d.id=?', $designation_id);
        $result = $this->performSelect($select);
        if (count($result['rows'])) {
            return Designation::hydrate($result['rows'][0]);
        }
        throw new \Exception('designations/unknown');
    }

    /**
     * Save an existing street designation back to the database
     */
    public function updateDesignation(\Domain\Streets\Designations\UseCases\Update\UpdateRequest $req)
    {
        $data = [
            'id'         => $req->designation_id,
            'type_id'    => $req->type_id,
            'start_date' => $req->start_date->format('c'),
            'rank'       => $req->rank
        ];
        parent::saveToTable($data, 'street_designations');
    }

    /**
     * @return array  An array of Street entities
     */
    public function intersectingStreets(int $street_id): array
    {
        $select = $this->queryFactory->newSelect();
        $select->distinct()
               ->cols($this->columns())
               ->from(         'road.centerlines           c')
               ->join('INNER', 'road.intersection_segments i',  'c.id=i.centerline_id')
               ->join('INNER', 'road.centerlines           x',  'i.intersection_id=x.low_intersection_id or i.intersection_id=x.high_intersection_id')
               ->join('INNER', 'streets                    s',  's.id=x.street_id')
               ->join('LEFT' , 'towns                   town',  's.town_id=town.id')
               ->join('INNER', 'street_designations        d',  's.id = d.street_id')
               ->join('INNER', 'street_names               n',  'n.id = d.street_name_id')
               ->join('LEFT',  'street_types               t',  't.id = n.suffix_code_id')
               ->join('INNER', 'street_designation_types  dt', 'dt.id = d.type_id')
               ->where('c.street_id=?', $street_id)
               ->where('s.id!=c.street_id'); // A street should not intersect iteself
        $select->orderBy(self::$DEFAULT_SORT);

        $query   = $this->pdo->prepare($select->getStatement());
        $query->execute($select->getBindValues());
        $result  = $query->fetchAll(\PDO::FETCH_ASSOC);

        $streets = [];
        foreach ($result as $r) {
            $streets[] = self::hydrateStreet($r);
        }
        return $streets;
    }

    /**
     * @return array  An array of Intersection entities
     */
    public function intersections(int $street_id_1, int $street_id_2): array
    {
        $sql = "select distinct i.*
                from road.centerlines           c
                join road.intersection_segments s on c.id=s.centerline_id
                join road.centerlines           x on s.intersection_id=x.low_intersection_id or s.intersection_id=x.high_intersection_id
                join road.intersections         i on i.id=s.intersection_id
                where c.street_id=? and x.street_id=?";
        $query = $this->pdo->prepare($sql);
        $query->execute([$street_id_1, $street_id_2]);
        $result = $query->fetchAll(\PDO::FETCH_ASSOC);

        $intersections = [];
        foreach ($result as $r) {
            $intersections[] = new Intersection($r);
        }
        return $intersections;
    }

    //---------------------------------------------------------------
    // Metadata Functions
    //---------------------------------------------------------------
    public function towns(): array
    {
        $result = $this->pdo->query('select id, name, code from towns order by name');
        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function types(): array
    {
        $result = $this->pdo->query('select * from street_types order by name');
        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function designationTypes(): array
    {
        $result = $this->pdo->query('select * from street_designation_types order by name');
        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }
}
