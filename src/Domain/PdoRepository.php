<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain;

use Aura\SqlQuery\Common\SelectInterface;
use Aura\SqlQuery\QueryFactory;

abstract class PdoRepository
{
    const DATE_FORMAT = 'Y-m-d';

    protected $pdo;
    protected $queryFactory;

    protected $tablename;
    protected $entityClass;
    protected $primaryKey = 'id';

    public function __construct(\PDO $pdo)
    {
        $this->pdo          = $pdo;
        $this->queryFactory = new QueryFactory(ucfirst($pdo->getAttribute(\PDO::ATTR_DRIVER_NAME)));
    }

    public function columns(): array
    {
        static $cols;
        if (!$cols) {
            foreach (get_class_vars($this->entityClass) as $k=>$v) {
                $cols[] = $k;
            }
        }
        return $cols;
    }

	public function performSelect(SelectInterface $select, int $itemsPerPage=null, int $currentPage=null) : array
	{
        $total = null;

        if ($itemsPerPage) {
            $currentPage = $currentPage ? $currentPage : 1;

            $c = $this->queryFactory->newSelect();
            $c->cols(['count(*) as count'])
              ->fromSubSelect($select, 'o');

            $query = $this->pdo->prepare($c->getStatement());
            $query->execute($c->getBindValues());

            $result = $query->fetchAll(\PDO::FETCH_ASSOC);
            $total  = (int)$result[0]['count'];

            $select->limit ($itemsPerPage);
            $select->offset($itemsPerPage * ($currentPage-1));
        }


        $query = $this->pdo->prepare($select->getStatement());
        $query->execute($select->getBindValues());

        return [
            'rows'  => $query->fetchAll(\PDO::FETCH_ASSOC),
            'total' => $total
        ];
	}

	protected function saveEntity($entity): int
	{
        $data = [];
        foreach ($entity as $k=>$v) {
            if ($k != $this->primaryKey) { $data[$k] = $v; }
        }

        if ($entity->id) {
            // Update
            $update = $this->queryFactory->newUpdate();
            $update->table($this->tablename)
                   ->cols($data)
                   ->where("{$this->primaryKey}=?", $entity->id);
            $query = $this->pdo->prepare($update->getStatement());
            $query->execute($update->getBindValues());
            return $entity->id;
        }
        else {
            // Insert
            $insert = $this->queryFactory->newInsert();
            $insert->into($this->tablename)
                   ->cols($data);
            $query = $this->pdo->prepare($insert->getStatement());
            $query->execute($insert->getBindValues());
            $pk = $insert->getLastInsertIdName($this->primaryKey);
            return (int)$this->pdo->lastInsertId($pk);
        }
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
}
