<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Towns\DataStorage;

use Domain\PdoRepository;

use Domain\Towns\Entities\Town;
use Domain\Towns\UseCases\Info\InfoRequest;
use Domain\Towns\UseCases\Search\SearchRequest;
use Domain\Towns\UseCases\Update\UpdateRequest;

class PdoTownsRepository extends PdoRepository implements TownsRepository
{
    protected $tablename   = 'towns';
    protected $entityClass = '\Domain\Towns\Entities\Town';

    public static $DEFAULT_SORT = ['name'];

    public function load(InfoRequest $req): Town
    {
        $select = $this->queryFactory->newSelect();
        $select->cols($this->columns())->from($this->tablename);
        $select->where('id=?', $req->id);
        $result = $this->performSelect($select);
        if (count($result['rows'])) {
            return new Town($result['rows'][0]);
        }
        throw new \Exception('towns/unknown');
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

        return $this->performSelect($select);
    }

    /**
     * Saves a town and returns the ID for the town
     */
    public function save(UpdateRequest $req): int
    {
        if ($req->id) {
            // Update
            $update = $this->queryFactory->newUpdate();
            $update->table($this->tablename)
                   ->cols(['name'=>$req->name, 'code'=>$req->code])
                   ->where('id=?', $req->id);
            $query = $this->pdo->prepare($update->getStatement());
            $query->execute($update->getBindValues());
            return $req->id;
        }
        else {
            // Insert
            $insert = $this->queryFactory->newInsert();
            $insert->into($this->tablename)
                   ->cols(['name'=>$req->name, 'code'=>$req->code]);
            $query = $this->pdo->prepare($insert->getStatement());
            $query->execute($insert->getBindValues());
            $pk = $insert->getLastInsertIdName('id');
            return (int)$this->pdo->lastInsertId($pk);
        }
    }
}
