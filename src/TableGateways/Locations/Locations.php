<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\TableGateways\Locations;

use Blossom\Classes\TableGateway;

class Locations extends TableGateway
{
    const DEFAULT_SORT = 'active';
    protected $columns = ['type_id', 'address_id', 'subunit_id', 'trash_day', 'recycle_week'];
    public function __construct() { parent::__construct('locations', 'Application\Models\Locations\Location'); }

	public function find(array $fields=null, array $order=null, int $itemsPerPage=null, int $currentPage=null)
	{
        $select = $this->queryFactory->newSelect();
        $select->cols(['l.*'])
               ->from('locations l');
		if (count($fields)) {
			foreach ($fields as $key=>$value) {
				switch ($key) {
					default:
                        if (in_array($key, $this->columns)) {
                            $value
                                ? $select->where("l.$key=?", $value)
                                : $select->where("l.$key is null");
                        }
				}
			}
		}
		if (!$order) { $order = [self::DEFAULT_SORT]; }
        $select->orderBy($order);
		return parent::performSelect($select, $itemsPerPage, $currentPage);
	}
}
