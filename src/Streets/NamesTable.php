<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Streets;

use Application\Addresses\Parser;
use Blossom\Classes\TableGateway;

class NamesTable extends TableGateway
{
    protected $columns = ['id', 'name', 'direction', 'post_direction', 'suffix_code_id'];

    public function __construct() { parent::__construct('street_names', 'Application\Streets\Name'); }

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
                    case 'street_id':
                        if (!isset($joins['l'])) { $joins['l'] = ['INNER', 'street_street_names l', 'n.id=l.street_name_id']; }
                    break;

                    case 'type_id':
                        if (!isset($joins['l'])) { $joins['l'] = ['INNER', 'street_street_names l', 'n.id=l.street_name_id']; }
                    break;

                    case Parser::STREET_TYPE:
                        if (!isset($joins['t'])) { $joins['t'] = ['INNER', 'street_types        t', 'n.suffix_code_id=t.id']; }
                    break;
                }
            }
        }
        return $joins;
    }

    public function find(array $fields=null, array $order=['name'], int $itemsPerPage=null, int $currentPage=null)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(['n.*'])
               ->from('street_names as n');

        if (count($fields)) {
			foreach ($fields as $key=>$value) {
                if (!empty($value)) {
                    switch ($key) {
                        case 'street_id':
                            $select->where('l.street_id=?', $value);
                        break;

                        case 'type_id':
                            $select->where('l.type_id=?', $value);
                        break;

                        case Parser::STREET_TYPE:
                            $select->where('t.code=?', $value);
                        break;

                        case Parser::DIRECTION:
                            $select->where('n.direction=?', $value);
                        break;

                        case Parser::STREET_NAME:
                            $select->where('n.name=?', $value);
                        break;

                        case Parser::POST_DIRECTION:
                            $select->where('n.post_direction=?', $value);
                        break;


                        default:
                            if (in_array($key, $this->columns)) {
                                $select->where("n.$key=?", $value);
                            }
                    }
                }
			}
        }

        foreach ($this->getJoins($fields) as $j) { $select->join($j[0], $j[1], $j[2]); }

        if ($order) { $select->orderBy($order); }
		return parent::performSelect($select, $itemsPerPage, $currentPage);
    }

    public function search(array $fields=null, array $order=['name'], int $itemsPerPage=null, int $currentPage=null)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(['n.*'])
               ->from('street_names as n');

        if (count($fields)) {
			foreach ($fields as $key=>$value) {
                if (!empty($value)) {
                    switch ($key) {
                        case 'street_id':
                            $select->where('l.street_id=?', $value);
                        break;

                        case 'type_id':
                            $select->where('l.type_id=?', $value);
                        break;

                        case Parser::STREET_TYPE:
                            $select->where('t.code=?', $value);
                        break;

                        case Parser::DIRECTION:
                            $select->where('n.direction=?', $value);
                        break;

                        case Parser::STREET_NAME:
                            $select->where('n.name like ?', "%$value%");
                        break;

                        case Parser::POST_DIRECTION:
                            $select->where('n.post_direction=?', $value);
                        break;


                        default:
                            if (in_array($key, $this->columns)) {
                                $select->where("n.$key=?", $value);
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
