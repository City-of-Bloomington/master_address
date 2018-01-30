<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Subdivisions\DataStorage;

use Aura\SqlQuery\Common\SelectInterface;
use Domain\PdoRepository;
use Domain\Subdivisions\Entities\Subdivision;
use Domain\Subdivisions\UseCases\Info\InfoRequest;
use Domain\Subdivisions\UseCases\Search\SearchRequest;
use Domain\Subdivisions\UseCases\Update\UpdateRequest;
use Domain\Townships\Entities\Township;

class PdoSubdivisionsRepository extends PdoRepository implements SubdivisionsRepository
{
    protected $tablename   = 'subdivisions';
    protected $entityClass = '\Domain\Subdivisions\Entities\Subdivision';

    public static $DEFAULT_SORT = ['name'];
    public function columns(): array
    {
        return [
            's.id', 's.name', 's.phase', 's.status', 's.township_id',
            't.name as township_name',
            't.code as township_code',
            't.quarter_code as township_quarter_code'
        ];
    }

    private function baseSelect(): SelectInterface
    {
        $select = $this->queryFactory->newSelect();
        $select->cols($this->columns())
               ->from("{$this->tablename} as s")
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
        foreach (parent::columns() as $f) {
            if (!empty($req->$f)) {
                switch ($f) {
                    case 'phase':
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

        $subdivisions = [];
        foreach ($result['rows'] as $r) { $subdivisions[] = self::hydrate($r); }
        $result['rows'] = $subdivisions;
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
        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Saves a subdivision and returns the ID for the subdivision
     */
    public function save(Subdivision $subdivision): int
    {
        // Remove the township fields from the data to be saved
        unset($subdivision->township_name);
        unset($subdivision->township_code);
        unset($subdivision->township_quarter_code);

        return parent::saveEntity($subdivision);
    }
}
