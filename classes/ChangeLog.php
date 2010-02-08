<?php
/**
 * Handles retreiving log information from any of the log tables.
 *
 * There are three log tables used so far.
 * All of the log tables have these same fields
 * This class will UNION all the log tables, if you ask for the information.
 *
 * @copyright 2009-2010 City of Bloomington, Indiana
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
	 * Returns the types of logs available
	 *
	 * @return array
	 */
	public static function getTypes()
	{
		return array_keys(self::$logTables);
	}

	/**
	 * Returns the known actions that occur in the change logs
	 *
	 * @return array
	 */
	public static function getActions()
	{
		return ChangeLogEntry::$actions;
	}

	/**
	 * Returns the earliest year found in the change logs.
	 * @return int
	 */
	public static function getFirstYear()
	{
		$sql = "select min(x) from (
					select to_char(min(action_date),'YYYY') as x from street_change_log
					union all
					select to_char(min(action_date),'YYYY') as x from address_change_log
					union all
					select to_char(min(action_date),'YYYY') as x from subunit_change_log
				)";
		$zend_db = Database::getConnection();
		return $zend_db->fetchOne($sql);
	}

	private static function getZendDbSelect(array $types, array $actions, array $fields=null, array $jurisdictions=null)
	{
		// Gather all the Where clauses and save them as a single where string
		$where = array();
		if (count($actions)) {
			$actionSet = array();
			foreach ($actions as $action) {
				$actionSet[] = "'$action'";
			}
			$actionSet = implode(',',$actionSet);
			$where[] = "action in ($actionSet)";
		}

		if ($fields) {
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
		$where = count($where) ? implode(' and ',$where) : '';

		// Prepare a Select statement for each of the tables we're looking through
		$zend_db = Database::getConnection();
		$allqq = array();
		foreach (self::$logTables as $type=>$data) {
			if (in_array($type,$types)) {
				$select = $zend_db->select()->from($data['table'],
													array('type'=>new Zend_Db_Expr("'$type'"),
														'id'=>$data['id'],
														'action','action_date','notes',
														'user_id','contact_id'));
				// Filter on jurisdictions only for Address queries
				if ($type=='Address') {
					$jurisdictions = implode(',',$jurisdictions);
					$select->joinLeft(array('a'=>'mast_address'),
											"a.$data[id]=$data[table].$data[id]",
											array());
					$select->where("a.gov_jur_id in ($jurisdictions)");
				}

				if ($where) {
					$select->where($where);
				}
				$allqq[] = $select;
			}
		}

		// Create a Union query using all of the Select statements we've prepared
		if (count($allqq)) {
			return $zend_db->select()->union($allqq)->order(array('type','action_date desc'));
		}
	}

	/**
	 * Returns an array of ChangeLogEntry
	 *
	 * Address Logs are filtered by Jurisdictions
	 *
	 * @param array $types The log tables to look through
	 * @param array $actions The relevant actions in the log tables
	 * @param array $fields  Additional fields to include in the where
	 * @param array $jurisdictions Jurisdictions to filter address
	 * @return array ChangeLogEntry
	 */
	public static function getEntries(array $types, array $actions, array $fields=null, array $jurisdictions=null)
	{
		$changeLog = array();

		$select = self::getZendDbSelect($types,$actions,$fields,$jurisdictions);
		if ($select) {
			$query = $select->query();
			$result = $query->fetchAll();
			foreach ($result as $row) {
				$changeLog[] = new ChangeLogEntry($row);
			}
		}
		return $changeLog;
	}

	/**
	 * Returns a Zend_Paginator for the raw database results
	 *
	 * If you ask for this, you must remember to create a ChangeLogEntry out of
	 * each row of the results.
	 * $changeLogEntry = new ChangeLogEntry($row)
	 *
	 * @param array $types The log tables to look through
	 * @param array $actions The relevant actions in the log tables
	 * @param array $fields  Additional fields to include in the where
	 * @return Zend_Paginator
	 */
	public static function getPaginator(array $types,array $actions,array $fields,array $jurisdictions)
	{
		$select = self::getZendDbSelect($types,$actions,$fields,$jurisdictions);
		if ($select) {
			return new Zend_Paginator(new Zend_Paginator_Adapter_DbSelect($select));
		}
	}
}
