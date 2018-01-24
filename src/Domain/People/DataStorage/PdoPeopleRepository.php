<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\People\DataStorage;

use Domain\PdoRepository;

use Domain\People\Entities\Person;
use Domain\People\UseCases\Info\InfoRequest;
use Domain\People\UseCases\Search\SearchRequest;
use Domain\People\UseCases\Update\UpdateRequest;

class PdoPeopleRepository extends PdoRepository implements PeopleRepository
{
    protected $tablename   = 'people';
    protected $entityClass = '\Domain\People\Entities\Person';

    public static $DEFAULT_SORT = ['lastname', 'firstname'];

    public function load(InfoRequest $req): Person
    {
        $select = $this->queryFactory->newSelect();
        $select->cols($this->columns())->from($this->tablename);
        $select->where('id=?', $req->id);
        $result = $this->performSelect($select);
        if (count($result['rows'])) {
            return new Person($result['rows'][0]);
        }
        throw new \Exception('people/unknown');
    }

    public function search(SearchRequest $req): array
    {
        $select = $this->queryFactory->newSelect();
        $select->cols($this->columns())->from($this->tablename);

        foreach ($this->columns() as $f) {
            if (!empty($req->$f)) {
                $select->where("$f like ?", $req->$f);
            }
        }
        $select->orderBy(self::$DEFAULT_SORT);

        return $this->performSelect($select, $req->itemsPerPage, $req->currentPage);
    }

    /**
     * Saves a person and returns the ID for the person
     */
    public function save(Person $person): int
    {
        return parent::saveEntity($person);
    }
}
