<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Models\TableGateways;

use Application\Models\Plat;
use Blossom\Classes\TableGateway;

class Plats extends TableGateway
{
    public function __construct() { parent::__construct('plats', 'Application\Models\Plat'); }

	/**
	 * @param array $fields       Key value pairs to select on
	 * @param array $order        The default ordering to use for select
	 * @param int   $itemsPerPage
	 * @param int   $currentPage
	 * @return array|Paginator
	 */
	public function find(array $fields=null, array $order=null, int $itemsPerPage=null, int $currentPage=null)
	{
        $select = $this->queryFactory->newSelect();
        $select->cols(['p.*'])
               ->from('plats as p');

		if (count($fields)) {
			foreach ($fields as $key=>$value) {
                if (!empty($value)) {
                    switch ($key) {
                        default:
                            if (in_array($key, Plat::$fieldmap)) {
                                $select->where("$key=?", $value);
                            }
                    }
                }
			}
		}

        if ($order) { $select->orderBy($order); }
		return parent::performSelect($select, $itemsPerPage, $currentPage);
	}

	/**
	 * @param array $fields       Key value pairs to select on
	 * @param array $order        The default ordering to use for select
	 * @param int   $itemsPerPage
	 * @param int   $currentPage
	 * @return array|Paginator
	 */
	public function search(array $fields=null, array $order=null, int $itemsPerPage=null, int $currentPage=null)
	{
        $select = $this->queryFactory->newSelect();
        $select->cols(['p.*'])
               ->from('plats as p');

		if (count($fields)) {
			foreach ($fields as $key=>$value) {
                if (!empty($value)) {
                    switch ($key) {
                        case 'township_id':
                            $select->where("$key=?", $value);
                        break;

                        case 'startDate':
                        case 'endDate':
                        break;

                        default:
                            if (in_array($key, Plat::$fieldmap)) {
                                $select->where("$key like ?", "%$value%");
                            }
                    }
                }
			}
		}

        if ($order) { $select->orderBy($order); }
		return parent::performSelect($select, $itemsPerPage, $currentPage);
	}
}
