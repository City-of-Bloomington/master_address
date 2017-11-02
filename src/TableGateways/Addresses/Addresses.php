<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\TableGateways\Addresses;

use Application\Models\Addresses\Address;
use Application\Models\Addresses\Parser;
use Aura\SqlQuery\Common\SelectInterface;
use Blossom\Classes\TableGateway;

class Addresses extends TableGateway
{
    const DEFAULT_SORT = 'street_number';

    public function __construct()
    {
        $this->columns = array_keys(Address::$fields);
        parent::__construct('addresses', 'Application\Models\Addresses\Address');
    }

    private function createSelect(array $fields=null) : SelectInterface
    {
        $select = $this->queryFactory->newSelect();
        $select->cols(['a.*'])
               ->from('addresses as a');

		if (isset($fields['latitude']) && isset($fields['longitude'])
			&& is_numeric($fields['latitude']) && is_numeric($fields['longitude'])) {
			$fields['latitude' ] = (float)$fields['latitude'];
			$fields['longitude'] = (float)$fields['longitude'];
			$select->cols([
                'a.*',
                "(sqrt(power(($fields[latitude] - latitude),2) + power(($fields[longitude] - longitude),2))) as distance"
            ]);
		}

        return $select;
    }

    private function getJoins($fields) : array
    {
        $joins = [];
        foreach ($fields as $k=>$v) {
            if (!empty($v)) {
                switch ($k) {
                    case 'location_id':
                        if (!isset($joins['l'])) { $joins['l'] = ['INNER', 'locations           l', 'a.id=l.address_id'       ]; }
                    break;
                    case Parser::DIRECTION:
                        if (!isset($joins['s'])) { $joins['s'] = ['INNER', 'street_street_names s', 's.street_id=a.street_id' ]; }
                        if (!isset($joins['n'])) { $joins['n'] = ['INNER', 'street_names        n', 's.street_name_id=n.id'   ]; }
                        if (!isset($joins['d'])) { $joins['d'] = ['INNER' , 'directions         d', 'n.direction_id=d.id'     ]; }
                    break;
                    case Parser::POST_DIRECTION:
                        if (!isset($joins['s'])) { $joins['s'] = ['INNER', 'street_street_names s', 's.street_id=a.street_id' ]; }
                        if (!isset($joins['n'])) { $joins['n'] = ['INNER', 'street_names        n', 's.street_name_id=n.id'   ]; }
                        if (!isset($joins['p'])) { $joins['p'] = ['INNER', 'directions          p', 'n.post_direction_id=d.id']; }
                    break;
                    case Parser::STREET_NAME:
                        if (!isset($joins['s'])) { $joins['s'] = ['INNER', 'street_street_names s', 's.street_id=a.street_id' ]; }
                        if (!isset($joins['n'])) { $joins['n'] = ['INNER', 'street_names        n', 's.street_name_id=n.id'   ]; }
                    break;
                    case Parser::STREET_TYPE:
                        if (!isset($joins['s'])) { $joins['s'] = ['INNER', 'street_street_names s', 's.street_id=a.street_id' ]; }
                        if (!isset($joins['n'])) { $joins['n'] = ['INNER', 'street_names        n', 's.street_name_id=n.id'   ]; }
                        if (!isset($joins['t'])) { $joins['t'] = ['INNER', 'street_types        t', 'n.suffix_code_id=t.id'   ]; }
                    break;
                    case Parser::SUBUNIT_TYPE:
                        if (!isset($joins['u'])) { $joins['u'] = ['INNER', 'subunits            u', 'a.id=u.address_id'       ]; }
                        if (!isset($joins['x'])) { $joins['x'] = ['INNER', 'subunit_types       x', 'u.type_id=x.id'          ]; }
                    break;
                    case Parser::SUBUNIT_ID:
                        if (!isset($joins['u'])) { $joins['u'] = ['INNER', 'subunits            u', 'a.id=u.address_id'       ]; }
                    break;
                }
            }
        }
        return $joins;
    }

	/**
	 * Populates the collection using exact matching
	 */
    public function find(array $fields=null, array $order=null, int $itemsPerPage=null, int $currentPage=null)
    {
        $select = $this->createSelect($fields);

        if (count($fields)) {
			foreach ($fields as $key=>$value) {
                if (is_string($value)) {
                    $value = trim($value);
                    $fields[$key] = $value;
                }

                if (!empty($value)) {
                    switch ($key) {
                        case 'location_id'         : $select->where('l.location_id=?', $value); break;
                        case Parser::DIRECTION     : $select->where('d.code=?',        $value); break;
                        case Parser::POST_DIRECTION: $select->where('p.code=?',        $value); break;
                        case Parser::STREET_NAME   : $select->where('n.name=?',        $value); break;
                        case Parser::STREET_TYPE   : $select->where('t.code=?',        $value); break;
                        case Parser::SUBUNIT_ID    : $select->where('u.identifier=?',  $value); break;
                        case Parser::SUBUNIT_TYPE:
                            if (empty($fields[Parser::SUBUNIT_ID])) {
                                $select->where('x.code=?', $value); break;
                            }
                        break;

                        default:
                            if (in_array($key, $this->columns)) {
                                $select->where("a.$key=?", $value);
                            }
                    }
                }
            }
        }

        foreach ($this->getJoins($fields) as $j) { $select->join($j[0], $j[1], $j[2]); }


        if (!$order) { $order = ['a.'.self::DEFAULT_SORT]; }
        $select->orderBy($order);
		return parent::performSelect($select, $itemsPerPage, $currentPage);
    }

	/**
	 * Populates the collection using loose matching
	 */
    public function search(array $fields=null, array $order=null, int $itemsPerPage=null, int $currentPage=null)
    {
        $select = $this->createSelect($fields);

        if (count($fields)) {
			foreach ($fields as $key=>$value) {
                if (is_string($value)) {
                    $value = trim($value);
                    $fields[$key] = $value;
                }

                if (!empty($value)) {
                    switch ($key) {
                        case 'location_id'         : $select->where('l.location_id=?', $value); break;
                        case Parser::DIRECTION     : $select->where('d.code=?',        $value); break;
                        case Parser::POST_DIRECTION: $select->where('p.code=?',        $value); break;
                        case Parser::STREET_NAME   : $select->where('n.name like ?',"$value%"); break;
                        case Parser::STREET_TYPE   : $select->where('t.code=?',        $value); break;
                        case Parser::SUBUNIT_ID    : $select->where('u.identifier=?',  $value); break;
                        case Parser::SUBUNIT_TYPE:
                            if (empty($fields[Parser::SUBUNIT_ID])) {
                                $select->where('x.code=?', $value); break;
                            }
                        break;

                        default:
                            if (in_array($key, $this->columns)) {
                                $select->where("a.$key like ?", "$value%");
                            }
                    }
                }
            }
        }

        foreach ($this->getJoins($fields) as $j) { $select->join($j[0], $j[1], $j[2]); }


        if (!$order) { $order = ['a.'.self::DEFAULT_SORT]; }
        $select->orderBy($order);
		return parent::performSelect($select, $itemsPerPage, $currentPage);
    }
}
