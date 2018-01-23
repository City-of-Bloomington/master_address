<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Users\DataStorage;

use Domain\PdoRepository;
use Domain\Users\UseCases\Search\SearchRequest;

class PdoUsersRepository extends PdoRepository implements UsersRepository
{
    protected $tablename   = 'people';
    protected $entityClass = '\Domain\Users\Entities\UserFields';

    public static $DEFAULT_SORT = ['lastname', 'firstname'];

    private function loadByKey(string $key, $value): array
    {
        $select = $this->queryFactory->newSelect();
        $select->cols($this->columns())->from('people');
        $select->where("$key=?", $value);
        $result = $this->performSelect($select);
        if ( count($result['rows'])) {
            return $result['rows'][0];
        }
    }
    public function loadById      (int    $id      ): array { return $this->loadByKey('id',       $id); }
    public function loadByUsername(string $username): array { return $this->loadByKey('username', $username); }

    public function find(SearchRequest $req): array
    {
        $select = $this->queryFactory->newSelect();
        $select->cols($this->columns())->from('people');

        foreach ($this->columns() as $f) {
            if (!empty($req->$f)) {
                $select->where("$f=?", $req->$f);
            }
        }
        $order = $req->order ? $req->order : self::$DEFAULT_SORT;
        $select->orderBy($order);

        echo $select->getStatement()."\n";
        $result = $this->performSelect($select, $req->itemsPerPage, $req->currentPage);
        return $result;
    }


    public function search(SearchRequest $req): array
    {
        $select = $this->queryFactory->newSelect();
        $select->cols($this->columns())->from('people');

        foreach ($this->columns() as $f) {
            if (!empty($req->$f)) {
                $select->where("$f like ?", $req->$f);
            }
        }
        $order = $req->order ? $req->order : self::$DEFAULT_SORT;
        $select->orderBy($order);

        $result = $this->performSelect($select, $req->itemsPerPage, $req->currentPage);
        return $result;
    }
}
