<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\TableGateways\Streets;

use Blossom\Classes\TableGateway;

class Names extends TableGateway
{
    protected $columns = ['id', 'name', 'direction_id', 'post_direction_id', 'suffix_code_id'];

    public function __construct() { parent::__construct('street_names', 'Application\Models\Streets\Name'); }

    public function find(array $fields=null, array $order=['name'], int $itemsPerPage=null, int $currentPage=null)
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(['n.*'])
               ->from('street_names as n');

        $joins = [];
        if (count($fields)) {
			foreach ($fields as $key=>$value) {
                if (!empty($value)) {
                    switch ($key) {
                        case 'street_id':
                            if (!isset($joins['l'])) { $joins['l'] = ['INNER', 'street_street_names l', 'n.id=l.street_name_id']; }
                            $select->where('l.street_id=?', $value);
                        break;

                        case 'type_id':
                            if (!isset($joins['l'])) { $joins['l'] = ['INNER', 'street_street_names l', 'n.id=l.street_name_id']; }
                            $select->where('l.type_id=?', $value);
                        break;

                        default:
                            if (in_array($key, $this->columns)) {
                                $select->where("n.$key=?", $value);
                            }
                    }
                }
			}
        }

        foreach ($joins as $j) { $select->join($j[0], $j[1], $j[2]); }

        if ($order) { $select->orderBy($order); }
		return parent::performSelect($select, $itemsPerPage, $currentPage);
    }
}
