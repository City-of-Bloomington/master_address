<?php
/**
 * @copyright 2013-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\People;

use Blossom\Classes\TableGateway;

class PeopleTable extends TableGateway
{
    public static $defaultSort = ['p.lastname'];
    private $columns = ['contact_type', 'firstname', 'lastname', 'email', 'phone', 'agency'];

    public function __construct() { parent::__construct('people', 'Application\People\Person'); }

	/**
	 * @param array $fields Key value pairs to select on
	 * @param array $order The default ordering to use for select
	 * @param int $itemsPerPage
	 * @param int $currentPage
	 * @return array|Paginator
	 */
	public function find(array $fields=null, array $order=null, int $itemsPerPage=null, int $currentPage=null)
	{
        $select = $this->queryFactory->newSelect();
        $select->cols(['p.*'])
               ->from('people as p');
		if (count($fields)) {
			foreach ($fields as $key=>$value) {
				switch ($key) {
					case 'user_account':
                        $select->where($value ? 'username is not null' : 'username is null');
					break;

					default:
                        if (in_array($key, $this->columns)) {
                            $select->where("$key=?", $value);
                        }
				}
			}
		}

		if (!$order) { $order = self::$defaultSort; }
        $select->orderBy($order);
		return parent::performSelect($select, $itemsPerPage, $currentPage);
	}

	public function search(array $fields=null, array $order=null, int $itemsPerPage=null, int $currentPage=null)
	{
        $select = $this->queryFactory->newSelect();
        $select->cols(['p.*'])
               ->from('people as p');
		if (count($fields)) {
			foreach ($fields as $key=>$value) {
				switch ($key) {
					case 'user_account':
                        $select->where($value ? 'username is not null' : 'username is null');
					break;

					default:
                        if (in_array($key, $this->columns)) {
                            $select->where("$key like ?", "$value%");
                        }
				}
			}
		}

		if (!$order) { $order = self::$defaultSort; }
        $select->orderBy($order);
		return parent::performSelect($select, $itemsPerPage, $currentPage);
	}
}
