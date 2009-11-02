<?php
/**
 * Handles retreiving log information from any of the log tables.
 *
 * There are three log tables used so far.
 * All of the log tables have these same fields
 * This class will UNION all the log tables, if you ask for the information.
 *
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class ChangeLog
{
	public static $logTables = array('Street'=>array('table'=>'street_change_log',
													'id'=>'street_id'),
									'Address'=>array('table'=>'address_change_log',
													'id'=>'street_address_id'),
									'Subunit'=>array('table'=>'subunit_change_log',
													'id'=>'subunit_id'));

	/**
	 * @param array $data
	 */
	public static function getEntries(array $types, array $actions, array $fields)
	{
		$where = array();
		if (count($actions)) {
			$actionSet = array();
			foreach ($actions as $action) {
				$actionSet[] = "'$action'";
			}
			$actionSet = implode(',',$actionSet);
			$where[] = "action in ($actionSet)";
		}

		if (count($fields)) {
			foreach ($fields as $key=>$value) {
				switch ($key) {
					case 'contact_id':
						$value = (int)$value;
						$where[] = "contact_id=$value";
						break;
					case 'dateFrom':
						$date = $value->format('Y-m-d');
						$where[] = "action_date>='$date'";
						break;
					case 'dateTo':
						$date = $value->format('Y-m-d');
						$where[] = "action_date<='$date'";
						break;
				}
			}
		}
		$where = count($where) ? 'where '.implode(' and ',$where) : '';

		$allqq = array();
		foreach (self::$logTables as $type=>$data) {
			if (in_array($type,$types)) {
				$allqq[] = "(select '$type' as type,
							$data[id] as id,action,action_date,notes,user_id,contact_id
							from $data[table] $where)";
			}
		}

		$changeLog = array();
		if (count($allqq)) {
			$sql = implode(' union ',$allqq);
			$sql.= 'order by type,action_date DESC';

			$zend_db = Database::getConnection();
			$result = $zend_db->fetchAll($sql);
			foreach ($result as $row) {
				$changeLog[] = new ChangeLogEntry($row);
			}
		}
		return $changeLog;
	}
}