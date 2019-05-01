<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Subdivisions\DataStorage;

use Aura\SqlQuery\Common\SelectInterface;
use Application\PdoRepository;
use Domain\Subdivisions\Entities\Subdivision;
use Domain\Subdivisions\UseCases\Info\InfoRequest;
use Domain\Subdivisions\UseCases\Search\SearchRequest;

class PdoSubdivisionsRepository extends PdoRepository implements SubdivisionsRepository
{
    const TABLE = 'subdivisions';

    public static $DEFAULT_SORT = ['name'];
    public static $fieldmap = [
        'id'                    => ['prefix'=>'s', 'dbName'=>'id'          ],
        'name'                  => ['prefix'=>'s', 'dbName'=>'name'        ],
        'phase'                 => ['prefix'=>'s', 'dbName'=>'phase'       ],
        'status'                => ['prefix'=>'s', 'dbName'=>'status'      ],
        'township_id'           => ['prefix'=>'s', 'dbName'=>'township_id' ],
        'township_name'         => ['prefix'=>'t', 'dbName'=>'name'        ],
        'township_code'         => ['prefix'=>'t', 'dbName'=>'code'        ],
        'township_quarter_code' => ['prefix'=>'t', 'dbName'=>'quarter_code']
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
               ->join('LEFT', 'townships as t', 's.township_id=t.id');
        return $select;
    }

    private static function hydrate(array $row): Subdivision
    {
        return new Subdivision($row);
    }

    public function load(InfoRequest $req): Subdivision
    {
        $select = $this->baseSelect();
        $select->where('s.id=?', $req->id);

        $result = $this->performSelect($select);
        if (count($result['rows'])) {
            return self::hydrate($result['rows'][0]);
        }
        throw new \Exception('subdivisions/unknown');
    }

    public function search(SearchRequest $req): array
    {
        $select = $this->baseSelect();
        foreach (self::$fieldmap as $f=>$m) {
            $column = "$m[prefix].$m[dbName]";
            if (!empty($req->$f)) {
                switch ($f) {
                    case 'phase':
                    case 'township_id':
                        $select->where("$column=?", $req->$f);
                    break;

                    default:
                        $select->where("lower($column) like ?", strtolower("{$req->$f}%"));
                }
            }
        }
        $select->orderBy(self::$DEFAULT_SORT);
        $result = $this->performSelect($select, $req->itemsPerPage, $req->currentPage);

        $subdivisions = [];
        foreach ($result['rows'] as $r) { $subdivisions[] = self::hydrate($r); }
        $result['rows'] = $subdivisions;
        return $result;
    }

    public function distinct(string $field): array
    {
        return parent::distinctFromTable($field, self::TABLE);
    }

    public function townships(): array
    {
        $result = $this->pdo->query('select id, name from townships order by name');
        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Saves a subdivision and returns the ID for the subdivision
     */
    public function save(Subdivision $s): int
    {
        return parent::saveToTable([
            'id'          => $s->id,
            'name'        => $s->name,
            'phase'       => $s->phase,
            'status'      => $s->status,
            'township_id' => $s->township_id
        ], self::TABLE);
    }
}
