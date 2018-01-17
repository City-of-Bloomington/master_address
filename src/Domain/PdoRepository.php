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
    protected $pdo;
    protected $queryFactory;

    public function __construct(\PDO $pdo)
    {
        $this->pdo          = $pdo;
        $this->queryFactory = new QueryFactory(ucfirst($pdo->getAttribute(\PDO::ATTR_DRIVER_NAME)));
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
            $select->offset($itemsPerPage * ($currentPageNumber-1));
        }


        $query = $this->pdo->prepare($select->getStatement());
        $query->execute($select->getBindValues());

        return [
            'rows'  => $query->fetchAll(\PDO::FETCH_ASSOC),
            'total' => $total
        ];
	}
}
