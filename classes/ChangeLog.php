<?php
/**
 * A class to encapsulate the information logged whenever someone makes a change
 * to something in the database.  All of the log tables will have these same fields
 *
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class ChangeLog
{

	/**
	 * find the list of change log entries
	 *
	 * Only the User is required.  You must pass the user in the first parameter.
	 * You can pass all the of the data as an array in the first paramter....
	 * Or you can pass the User and the data seperately
	 *
	 * @param User|array $user
	 * @param array $data
	 */
	public function __construct()
	{

	}

	/**
	 * @param array $data
	 */
	public static function getEntries(array $types, array $actions, array $fields)
	{
		$changeLog = array();
		$where='';
		$values = array();
		$allValues = array();
		$allqq = array();
		$zend_db = Database::getConnection();
		
		if($actions != null && count($actions) > 0){
			$actionSet = '';
			foreach($actions as $action){
				if($actionSet != '') $actionSet .=', ';
				$actionSet .= "'".$action."'"; 
			}
			$where .=' action in ('.$actionSet.')';
		}
		if($fields != null && count($fields) > 0){
			foreach($fields as $key=>$value){
				switch($key){
					case 'contact_id':
						if($where != '') $where .= ' and ';
						$where .=' contact_id=?';
						$values[] = $value;
						break;
					case 'dateFrom':
						if($where != '') $where .= ' and ';
						$where .=' action_date >=?';
						$values[] = $value->format('Y-m-d');
						break;
					case 'dateTo':
						if($where != '') $where .= ' and ';
						$where .=' action_date <=?';
						$values[] = $value->format('Y-m-d');
				}
			}
		}
		foreach($types as $type){
			switch($type){
				case 'streets':
					$qq = "select 'Street' as type,street_id as id,action,action_date,notes,user_id,contact_id from street_change_log ";
					if($where != ''){
						$qq .=' where '.$where;
						$allValues = $values;
					}
					$allqq[] = $qq;
				break;
				case 'addresses':
					$qq = "select 'Address' as type,street_address_id as id,action,action_date,notes,user_id,contact_id from address_change_log ";
					if($where != ''){
						$qq .=' where '.$where;
						$allValues = array_merge($allValues,$values);
					}
					$allqq[] = $qq;
				break;
				case 'subunits':
					$qq = "select 'Subunit' as type,subunit_id as id,action,action_date,notes,user_id,contact_id from subunit_change_log ";
					if($where != ''){
						$qq .= ' where '.$where;
						$allValues = array_merge($allValues,$values);
					}
					$allqq[] = $qq;
			}
		}
		

		if(count($allqq) >  1){
			$qq = ' select * from (';
			foreach($allqq as $key=>$q){
				if($key > 0) $qq .= ' union ';
				$qq .=" ($q) ";
			}
			$qq .= ') order by type,action_date DESC ';
		}
		else{
			$qq .= ' order by type,action_date DESC';
		}
		
		$result = $zend_db->fetchAll($qq,$allValues);
		foreach ($result as $row) {
			$changeLog[] = new ChangeLogEntry($row);
		}
		return $changeLog;		
		
	}

}