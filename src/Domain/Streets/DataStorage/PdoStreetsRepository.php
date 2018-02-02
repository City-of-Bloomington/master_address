<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\DataStorage;

use Aura\SqlQuery\Common\SelectInterface;
use Domain\PdoRepository;
use Domain\ChangeLogs\ChangeLogEntry;
use Domain\Streets\Entities\Street;
use Domain\Streets\UseCases\Info\InfoRequest;
use Domain\Streets\UseCases\Search\SearchRequest;
use Domain\Streets\UseCases\Update\UpdateRequest;
use Domain\Townships\Entities\Township;

class PdoStreetsRepository extends PdoRepository implements StreetsRepository
{
    const TYPE_STREET = 1;

    protected $tablename   = 'streets';
    protected $entityClass = '\Domain\Streets\Entities\Street';

    public static $DEFAULT_SORT = ['n.name'];
    public function columns(): array
    {
        return [
            's.id', 's.status', 's.notes', 's.town_id',
            'town.name as town_name', 'town.code as town_code',
            'n.id as name_id', 'n.direction', 'n.name', 'n.post_direction',
            't.code as suffix_code'
        ];
    }

    private function baseSelect(): SelectInterface
    {
        $select = $this->queryFactory->newSelect();
        $select->cols($this->columns())
               ->from("{$this->tablename}          s")
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

    public function load(InfoRequest $req): Street
    {
        $select = $this->baseSelect();
        $select->where('s.id=?', $req->id);

        $result = $this->performSelect($select);
        if (count($result['rows'])) {
            return self::hydrateStreet($result['rows'][0]);
        }
        throw new \Exception('streets/unknown');
    }

    public function search(SearchRequest $req): array
    {
        $select = $this->baseSelect();
        foreach (parent::columns() as $f) {
            if (!empty($req->$f)) {
                switch ($f) {
                    case 'id':
                    case 'status':
                    case 'town_id':
                        $select->where("s.$f=?", $req->$f);
                    break;

                    case 'suffix_code':
                        $select->where("t.code=?", $req->$f);
                    break;

                    case 'direction':
                    case 'post_direction':
                        $select->where("n.$f=?", $req->$f);
                    break;

                    case 'name':
                        $select->where("n.$f like ?", $req->$f);
                    break;
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
     * Saves a street and returns the ID for the street
     */
    public function save(Street $street): int
    {
        return parent::saveEntity([
            'id'      => $street->id,
            'town_id' => $street->town_id,
            'status'  => $street->status,
            'notes'   => $street->notes
        ]);
    }

    public function changeLog(int $street_id): array
    {
        $changeLog = [];
        $sql = "select l.street_id as entity_id,
                       l.id, l.person_id, l.contact_id, l.action_date, l.action, l.notes,
                       p.firstname as  person_firstname, p.lastname as  person_lastname,
                       c.firstname as contact_firstname, c.lastname as contact_lastname
                from street_change_log l
                left join people        p on l.person_id=p.id
                left join people        c on l.contact_id=p.id
                where street_id=?";

        $query = $this->pdo->prepare($sql);
        $query->execute([$street_id]);
        foreach ($query->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            $changeLog[] = ChangeLogEntry::hydrate($row);
        }
        return $changeLog;
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
}
