<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Users\DataStorage;

use Domain\PdoRepository;
use Domain\Users\Entities\User;
use Domain\Users\UseCases\Search\SearchRequest;

class PdoUsersRepository extends PdoRepository implements UsersRepository
{
    protected $tablename   = 'people';
    protected $entityClass = '\Domain\Users\Entities\User';

    public static $DEFAULT_SORT = ['lastname', 'firstname'];

    private function getBaseSelect()
    {
        $select = $this->queryFactory->newSelect();
        $select->cols($this->columns())->from('people');
        $select->where('username is not null');
        return $select;
    }

    private function loadByKey(string $key, $value): ?User
    {
        $select = $this->getBaseSelect();
        $select->where("$key=?", $value);
        $result = $this->performSelect($select);
        if ( count($result['rows'])) {
            return new User($result['rows'][0]);
        }
    }
    public function loadById      (int    $id      ): ?User { return $this->loadByKey('id',       $id); }
    public function loadByUsername(string $username): ?User { return $this->loadByKey('username', $username); }

    public function find(SearchRequest $req): array
    {
        $select = $this->getBaseSelect();

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
        $select = $this->getBaseSelect();

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

    /**
     * Saves and returns the ID
     */
    public function save(User $user): int
    {
        return parent::saveEntity($user);
    }

    public function delete(int $id)
    {
        $update = $this->queryFactory->newUpdate();
        $update->table($this->tablename)
               ->where('id=?', $id)
               ->cols([
                    'username' => null,
                    'password' => null,
                    'role'     => null,
                    'authentication_method' => null
                ]);
        $query = $this->pdo->prepare($update->getStatement());
        $query->execute($update->getBindValues());
    }
}
