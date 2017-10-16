<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Models\TableGateways;

use Blossom\Classes\TableGateway;

class Subdivisions extends TableGateway
{
    protected $columns = ['name', 'township_id', 'phase', 'status'];
    public static $defaultOrder = ['name'];

    public function __construct() { parent::__construct('subdivisions', 'Application\Models\Subdivision'); }

    public function find(array $fields=null, array $order=null, int $itemsPerPage=null, int $currentPage=null)
    {
        if (!$order) { $order = self::$defaultOrder; }

        return parent::find($fields, $order, $itemsPerPage, $currentPage);
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
        if (!$order) { $order = self::$defaultOrder; }

        $select = $this->queryFactory->newSelect();
        $select->cols(['s.*'])
               ->from('subdivisions as s');

		if (count($fields)) {
			foreach ($fields as $key=>$value) {
                if (!empty($value)) {
                    switch ($key) {
                        case 'name':
                            $select->where("$key like ?", "%$value%");
                        break;

                        default:
                            if (in_array($key, $this->columns)) {
                                $select->where("$key=?", $value);
                            }
                    }
                }
			}
		}

        if ($order) { $select->orderBy($order); }
		return parent::performSelect($select, $itemsPerPage, $currentPage);
	}
}
