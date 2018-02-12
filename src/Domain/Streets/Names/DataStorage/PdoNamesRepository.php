<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\Names\DataStorage;

use Domain\PdoRepository;

use Aura\SqlQuery\Common\SelectInterface;
use Domain\Streets\Names\Entities\Name;
use Domain\Streets\Names\UseCases\Search\SearchRequest;
use Domain\Streets\Names\UseCases\Update\UpdateRequest;

class PdoNamesRepository extends PdoRepository implements NamesRepository
{
    protected $tablename   = 'street_names';
    protected $entityClass = '\Domain\StreetNames\Entities\StreetName';

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
               ->from("{$this->tablename}   n")
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

    /**
     * Saves a name and returns the ID for the name
     */
    public function save(Name $name): int
    {
        return parent::saveEntity($name);
    }
}
