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
    public static $FIELDS = [
        'id', 'firstname', 'lastname', 'email',
        'username', 'role', 'authentication_method'
    ];

    public static $DEFAULT_SORT = ['lastname', 'firstname'];

    private function loadByKey(string $key, $value): User
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(self::$FIELDS)->from('people');
        $select->where("$key=?", $value);
        $result = $this->performSelect($select);
        if (count($result['rows'])) {
            return new User($result['rows'][0]);
        }
        throw new \Exception('users/unknown');
    }
    public function loadById      (int    $id      ): User { return $this->loadByKey('id',       $id); }
    public function loadByUsername(string $username): User { return $this->loadByKey('username', $username); }


    public function search(SearchRequest $req): array
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(self::$FIELDS)->from('people');

        foreach (self::$FIELDS as $f) {
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
