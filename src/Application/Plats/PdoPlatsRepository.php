<?php
/**
 * @copyright 2018-2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Plats;
use Domain\Plats\DataStorage\PlatsRepository;

use Aura\SqlQuery\Common\SelectInterface;
use Application\PdoRepository;
use Domain\Plats\Entities\Plat;
use Domain\Plats\UseCases\Info\InfoRequest;
use Domain\Plats\UseCases\Search\SearchRequest;

class PdoPlatsRepository extends PdoRepository implements PlatsRepository
{
    const TABLE = 'plats';

    public static $DEFAULT_SORT = ['name'];
    public static $fieldmap = [
        'id'                    => ['prefix'=>'p', 'dbName'=>'id'          ],
        'name'                  => ['prefix'=>'p', 'dbName'=>'name'        ],
        'plat_type'             => ['prefix'=>'p', 'dbName'=>'plat_type'   ],
        'cabinet'               => ['prefix'=>'p', 'dbName'=>'cabinet'     ],
        'envelope'              => ['prefix'=>'p', 'dbName'=>'envelope'    ],
        'start_date'            => ['prefix'=>'p', 'dbName'=>'start_date'  ],
        'end_date'              => ['prefix'=>'p', 'dbName'=>'end_date'    ],
        'notes'                 => ['prefix'=>'p', 'dbName'=>'notes'       ],
        'township_id'           => ['prefix'=>'p', 'dbName'=>'township_id' ],
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
               ->from(self::TABLE.' as p')
               ->join('LEFT', 'townships as t', 'p.township_id=t.id');
        return $select;
    }

    private function doSelect(SelectInterface $select,
                              ?int $itemsPerPage = null,
                              ?int $currentPage  = null): array
    {
        $select->orderBy(self::$DEFAULT_SORT);
        $result = $this->performSelect($select, $itemsPerPage, $currentPage);

        $plats = [];
        foreach ($result['rows'] as $r) { $plats[] = self::hydrate($r); }
        $result['rows'] = $plats;
        return $result;
    }


    private static function hydrate(array $row): Plat
    {
        if (!empty($row['start_date'])) { $row['start_date'] = new \DateTime($row['start_date']); }
        if (!empty($row['end_date'  ])) { $row['end_date'  ] = new \DateTime($row['end_date'  ]); }
        return new Plat($row);
    }

    public function load(InfoRequest $req): Plat
    {
        $select = $this->baseSelect();
        $select->where('p.id=?', $req->id);

        $result = $this->performSelect($select);
        if (count($result['rows'])) {
            return self::hydrate($result['rows'][0]);
        }
        throw new \Exception('plats/unknown');
    }

    /**
     * Find plats using exact matching
     */
    public function find(FindRequest $req): array
    {
        $select = $this->baseSelect();
        foreach (self::$fieldmap as $f=>$m) {
            $column = "$m[prefix].$m[dbName]";
            if (!empty($req->$f)) {
                $select->where("$column=?", $req->$f);
            }
        }
        return $this->doSelect($select, $req->itemsPerPage, $req->currentPage);
    }

    /**
     * Find plats using wildcard searching
     */
    public function search(SearchRequest $req): array
    {
        $select = $this->baseSelect();
        foreach (self::$fieldmap as $f=>$m) {
            $column = "$m[prefix].$m[dbName]";
            if (!empty($req->$f)) {
                switch ($f) {
                    case 'cabinet':
                    case 'plat_type':
                    case 'township_id':
                        $select->where("$column=?", $req->$f);
                    break;

                    default:
                        $select->where("lower($column) like ?", strtolower("{$req->$f}%"));
                }
            }
        }
        return $this->doSelect($select, $req->itemsPerPage, $req->currentPage);
    }

    public function townships(): array
    {
        $result = $this->pdo->query('select id, name from townships order by name');
        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Saves a plat and returns the ID for the plat
     */
    public function save(Plat $plat): int
    {
        return parent::saveToTable([
            'id'          => $plat->id,
            'name'        => $plat->name,
            'plat_type'   => $plat->plat_type,
            'cabinet'     => $plat->cabinet,
            'envelope'    => $plat->envelope,
            'notes'       => $plat->notes,
            'township_id' => $plat->township_id,
            'start_date'  => $plat->start_date ? $plat->start_date->format(parent::DATE_FORMAT) : null,
            'end_date'    => $plat->  end_date ? $plat->  end_date->format(parent::DATE_FORMAT) : null
        ],
        self::TABLE);
    }

    public function distinct(string $field): array
    {
        return parent::distinctFromTable($field, self::TABLE);
    }
}
