<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\Names\DataStorage;

use Domain\PdoRepository;

use Aura\SqlQuery\Common\SelectInterface;
use Domain\Streets\Entities\Designation;
use Domain\Streets\Entities\Name;
use Domain\Streets\Names\UseCases\Search\SearchRequest;

class PdoNamesRepository extends PdoRepository implements NamesRepository
{
    const TABLE = 'street_names';

    public static $DEFAULT_SORT = ['name'];

    public function columns(): array
    {
        return [
            'n.id', 'n.direction', 'n.name', 'n.post_direction', 'n.notes',
            'n.suffix_code_id', 't.code as suffix_code', 't.name as suffix_name'
        ];
    }

    private function baseSelect(): SelectInterface
    {
        $select = $this->queryFactory->newSelect();
        $select->cols($this->columns())
               ->from(self::TABLE.' n')
               ->join('LEFT', 'street_types t', 'n.suffix_code_id=t.id');
        return $select;
    }

    public function load(int $id): Name
    {
        $select = $this->baseSelect();
        $select->where('n.id=?', $id);
        $result = $this->performSelect($select);
        if (count($result['rows'])) {
            return new Name($result['rows'][0]);
        }
        throw new \Exception('streetNames/unknown');
    }

    public function search(SearchRequest $req): array
    {
        $select = $this->baseSelect();

        $fields = ['id', 'direction', 'post_direction', 'suffix_code', 'name'];
        foreach ($fields as $f) {
            if (!empty($req->$f)) {
                switch ($f) {
                    case 'name':
                        $select->where("n.$f like ?", "{$req->$f}%");
                    break;

                    case 'suffix_code':
                        $select->where("t.code=?", $req->$f);
                    break;

                    default:
                        $select->where("n.$f=?", $req->$f);
                }
            }
        }

        $select->orderBy(self::$DEFAULT_SORT);

        $result = $this->performSelect($select);
        $names  = [];
        foreach ($result['rows'] as $r) { $names[] = new Name($r); }
        $result['rows'] = $names;
        return $result;
    }

    public function designations(int $name_id): array
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
                where n.id=?";

        $query = $this->pdo->prepare($sql);
        $query->execute([$name_id]);
        foreach ($query->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            $designations[] = Designation::hydrate($row);
        }
        return $designations;
    }

    /**
     * Saves a name and returns the ID for the name
     */
    public function save(Name $name): int
    {
        return parent::saveToTable([
            'id'             => $name->id,
            'direction'      => $name->direction,
            'name'           => $name->name,
            'post_direction' => $name->post_direction,
            'suffix_code_id' => $name->suffix_code_id,
            'notes'          => $name->notes
        ],
        self::TABLE);
    }
}
