<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Streets;

use Application\Addresses\Parser;
use Blossom\Classes\TableGateway;

class StreetsTable extends TableGateway
{
	private $columns = ['id', 'town_id', 'status', 'notes'];

    public function __construct() { parent::__construct('streets', 'Application\Streets\Street'); }

    /**
     * Returns an array of join definitions
     *
     * @return array
     */
    private function getJoins($fields) : array
    {
        $joins = [];
        foreach ($fields as $k=>$v) {
            if (!empty($v)) {
                switch ($k) {
                    case Parser::STREET_NAME:
                        if (!isset($joins['l'])) { $joins['l'] = ['INNER', 'street_street_names l', 's.id=l.street_id'     ]; }
                        if (!isset($joins['n'])) { $joins['n'] = ['INNER', 'street_names        n', 'l.street_name_id=n.id']; }
                    break;

                    case Parser::DIRECTION:
                        if (!isset($joins['l'])) { $joins['l'] = ['INNER', 'street_street_names l', 's.id=l.street_id'     ]; }
                        if (!isset($joins['n'])) { $joins['n'] = ['INNER', 'street_names        n', 'l.street_name_id=n.id']; }
                        if (!isset($joins['d'])) { $joins['d'] = ['INNER', 'directions          d', 'n.direction_id=d.id'  ]; }
                    break;

                    case Parser::POST_DIRECTION:
                        if (!isset($joins['l'])) { $joins['l'] = ['INNER', 'street_street_names l', 's.id=l.street_id'     ]; }
                        if (!isset($joins['n'])) { $joins['n'] = ['INNER', 'street_names        n', 'l.street_name_id=n.id']; }
                        if (!isset($joins['p'])) { $joins['p'] = ['INNER', 'directions          d', 'n.post_direction_id=d.id']; }
                    break;

                    case Parser::STREET_TYPE:
                        if (!isset($joins['l'])) { $joins['l'] = ['INNER', 'street_street_names l', 's.id=l.street_id'     ]; }
                        if (!isset($joins['n'])) { $joins['n'] = ['INNER', 'street_names        n', 'l.street_name_id=n.id']; }
                        if (!isset($joins['t'])) { $joins['t'] = ['INNER', 'street_types        t', 'n.suffix_code_id=t.id']; }
                    break;
                }
            }
        }
        return $joins;
    }

    public function find(array $fields=null, array $order=['id'], int $itemsPerPage=null, int $currentPage=null)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(['s.*'])
               ->from('streets as s');

        if (count($fields)) {
			foreach ($fields as $key=>$value) {
                if (!empty($value)) {
                    switch ($key) {
                        case Parser::STREET_NAME:
                            $select->where('n.name=?', $value);
                        break;

                        case Parser::DIRECTION:
                            $select->where('d.code=?', $value);
                        break;

                        case Parser::POST_DIRECTION:
                            $select->where('p.code=?', $value);
                        break;

                        case Parser::STREET_TYPE:
                            $select->where('t.code=?', $value);
                        break;

                        default:
                            if (in_array($key, $this->columns)) {
                                $select->where("s.$key=?", $value);
                            }
                    }
                }
			}
        }

        foreach ($this->getJoins($fields) as $j) { $select->join($j[0], $j[1], $j[2]); }

        if ($order) { $select->orderBy($order); }
		return parent::performSelect($select, $itemsPerPage, $currentPage);
    }

    public function search(array $fields=null, array $order=['id'], int $itemsPerPage=null, int $currentPage=null)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(['s.*'])
               ->from('streets as s');

        if (count($fields)) {
			foreach ($fields as $key=>$value) {
                if (!empty($value)) {
                    switch ($key) {
                        case Parser::STREET_NAME:
                            $select->where('n.name like ?', "%$value%");
                        break;

                        case Parser::DIRECTION:
                            $select->where('d.code=?', $value);
                        break;

                        case Parser::POST_DIRECTION:
                            $select->where('p.code=?', $value);
                        break;

                        case Parser::STREET_TYPE:
                            $select->where('t.code=?', $value);
                        break;

                        default:
                            if (in_array($key, $this->columns)) {
                                $select->where("s.$key=?", $value);
                            }
                    }
                }
			}
        }

        foreach ($this->getJoins($fields) as $j) { $select->join($j[0], $j[1], $j[2]); }

        if ($order) { $select->orderBy($order); }
		return parent::performSelect($select, $itemsPerPage, $currentPage);
    }

}
