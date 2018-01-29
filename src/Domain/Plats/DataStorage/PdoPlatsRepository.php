<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Plats\DataStorage;

use Aura\SqlQuery\Common\SelectInterface;
use Domain\PdoRepository;
use Domain\Plats\Entities\Plat;
use Domain\Plats\UseCases\Info\InfoRequest;
use Domain\Plats\UseCases\Search\SearchRequest;
use Domain\Plats\UseCases\Update\UpdateRequest;
use Domain\Townships\Entities\Township;

class PdoPlatsRepository extends PdoRepository implements PlatsRepository
{
    protected $tablename   = 'plats';
    protected $entityClass = '\Domain\Plats\Entities\Plat';

    public static $DEFAULT_SORT = ['name'];
    public function columns(): array
    {
        return [
            'p.id', 'p.plat_type', 'p.name', 'p.cabinet', 'p.envelope', 'p.notes',
            'p.township_id', 'p.start_date', 'p.end_date',
            't.name as township_name',
            't.code as township_code',
            't.quarter_code as township_quarter_code'
        ];
    }

    private function baseSelect(): SelectInterface
    {
        $select = $this->queryFactory->newSelect();
        $select->cols($this->columns())
               ->from("{$this->tablename} as p")
               ->join('LEFT', 'townships as t', 'p.township_id=t.id');
        return $select;
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
        $select->where('id=?', $req->id);

        $result = $this->performSelect($select);
        if (count($result['rows'])) {
            return self::hydrate($result['rows'][0]);
        }
        throw new \Exception('plats/unknown');
    }

    public function search(SearchRequest $req): array
    {
        $select = $this->baseSelect();
        foreach (parent::columns() as $f) {
            if (!empty($req->$f)) {
                switch ($f) {
                    case 'cabinet':
                    case 'plat_type':
                    case 'township_id':
                        $select->where("$f=?", $req->$f);
                    break;

                    default:
                        $select->where("$f like ?", $req->$f);
                }
            }
        }
        $select->orderBy(self::$DEFAULT_SORT);
        $result = $this->performSelect($select, $req->itemsPerPage, $req->currentPage);

        $plats = [];
        foreach ($result['rows'] as $r) { $plats[] = self::hydrate($r); }
        $result['rows'] = $plats;
        return $result;
    }

    public function distinct(string $field): array
    {
        $select = $this->queryFactory->newSelect();
        $select->distinct()
               ->cols([$field])
               ->from($this->tablename)
               ->where("$field is not null")
               ->orderBy([$field]);

        $result = $this->pdo->query($select->getStatement());
        return $result->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function townships(): array
    {
        $result = $this->pdo->query('select id, name from townships order by name');
        return $result->fetchAll();
    }

    /**
     * Saves a plat and returns the ID for the plat
     */
    public function save(Plat $plat): int
    {
        return parent::saveEntity($plat);
    }
}
