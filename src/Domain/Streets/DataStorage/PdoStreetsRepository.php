<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\DataStorage;

use Aura\SqlQuery\Common\SelectInterface;
use Domain\PdoRepository;
use Domain\Logs\Entities\ChangeLogEntry;
use Domain\Streets\Entities\Designation;
use Domain\Streets\Entities\Street;
use Domain\Streets\UseCases\Add\AddRequest;
use Domain\Streets\UseCases\Alias\AliasRequest;
use Domain\Streets\UseCases\Info\InfoRequest;
use Domain\Streets\UseCases\Search\SearchRequest;
use Domain\Streets\UseCases\Update\UpdateRequest;
use Domain\Townships\Entities\Township;

class PdoStreetsRepository extends PdoRepository implements StreetsRepository
{
    use \Domain\Logs\DataStorage\ChangeLogTrait;
    protected $logType = 'street';

    const TYPE_STREET = 1;
    const TABLE = 'streets';

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
        'suffix_code'    => ['prefix'=>'t',    'dbName'=>'code'          ]
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
               ->join('LEFT', 'towns            town', 's.town_id=town.id')
               ->join('LEFT', 'street_designations d', 's.id=d.street_id and d.type_id='.self::TYPE_STREET)
               ->join('LEFT', 'street_names        n', 'd.street_name_id=n.id')
               ->join('LEFT', 'street_types        t', 'n.suffix_code_id=t.id');
        return $select;
    }

    private static function hydrateStreet(array $row): Street
    {
        return new Street($row);
    }

    public function load(int $street_id): Street
    {
        $select = $this->baseSelect();
        $select->where('s.id=?', $street_id);

        $result = $this->performSelect($select);
        if (count($result['rows'])) {
            return self::hydrateStreet($result['rows'][0]);
        }
        throw new \Exception('streets/unknown');
    }

    public function search(SearchRequest $req): array
    {
        $select = $this->baseSelect();
        foreach (self::$fieldmap as $f=>$m) {
            if (!empty($req->$f)) {
                $column = "$m[prefix].$m[dbName]";
                switch ($f) {
                    case 'name':
                        $select->where("$column like ?", "{$req->$f}%");
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

    /**
     * Saves a new street to the database and returns the ID for the street
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
            $designation    = new AliasRequest($street_id, $req->user_id, (array)$req);
            $designation_id = $this->addDesignation($designation);
            if ($designation_id) {
                $this->pdo->commit();
                return $street_id;
            }
        }
        $this->pdo->rollBack();
        throw new \Exception('databaseError');
    }

    public function update(UpdateRequest $req)
    {
        $sql = 'update streets set town_id=?, notes=? where id=?';
        $query = $this->pdo->prepare($sql);
        $query->execute([$req->town_id, $req->notes, $req->street_id]);
    }

    public function saveStatus(int $street_id, string $status)
    {
        $sql = 'update streets set status=? where id=?';
        $query = $this->pdo->prepare($sql);
        $query->execute([$status, $street_id]);
    }

    /**
     * @return int  The new designation_id
     */
    public function addDesignation(AliasRequest $req): int
    {
        $now = new \DateTime();
        return parent::saveToTable([
            'street_id'      => $req->street_id,
            'street_name_id' => $req->name_id,
            'type_id'        => $req->type_id,
            'rank'           => $req->rank,
            'start_date'     => $now->format('c')
        ], 'street_designations');
    }

    public function designations(int $street_id): array
    {
        $designations = [];
        $sql = "select  d.id,
                        d.street_id,
                        d.street_name_id as name_id,
                        d.type_id,
                        d.start_date,
                        d.end_date,
                        d.rank,
                        dt.name          as type,
                        n.direction,
                        n.name,
                        n.post_direction,
                        t.code           as suffix_code
                from street_designations d
                     join street_designation_types dt on d.type_id=dt.id
                     join street_names n on d.street_name_id=n.id
                left join street_types t on n.suffix_code_id=t.id
                where d.street_id=?";

        $query = $this->pdo->prepare($sql);
        $query->execute([$street_id]);
        foreach ($query->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            $designations[] = Designation::hydrate($row);
        }
        return $designations;
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
