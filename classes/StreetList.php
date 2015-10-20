<?php
/**
 * A collection class for Street objects
 *
 * This class creates a zend_db select statement.
 * ZendDbResultIterator handles iterating and paginating those results.
 * As the results are iterated over, ZendDbResultIterator will pass each desired
 * row back to this class's loadResult() which will be responsible for hydrating
 * each Street object
 *
 * Beyond the basic $fields handled, you will need to write your own handling
 * of whatever extra $fields you need
 *
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class StreetList extends ZendDbResultIterator
{
	private $columns = array('street_id','town_id','status_code','notes');

	/**
	 * Creates a basic select statement for the collection.
	 *
	 * Populates the collection if you pass in $fields
	 * Setting itemsPerPage turns on pagination mode
	 * In pagination mode, this will only load the results for one page
	 *
	 * @param array $fields
	 * @param int $itemsPerPage Turns on Pagination
	 * @param int $currentPage
	 */
	public function __construct($fields=null,$itemsPerPage=null,$currentPage=null)
	{
		parent::__construct($itemsPerPage,$currentPage);

		if (is_array($fields)) {
			$this->find($fields);
		}
	}

	/**
	 * Creates the base select query that this class uses.
	 * Both find() and search() will use the same select query.
	 */
	private function createSelection()
	{
		$this->select->distinct()->from(array('s'=>'mast_street'));
	}

	/**
	 * Populates the collection
	 *
	 * @param array $fields
	 * @param string|array $order Multi-column sort should be given as an array
	 * @param int $limit
	 * @param string|array $groupBy Multi-column group by should be given as an array
	 */
	public function find($fields=null,$order='street_id',$limit=null,$groupBy=null)
	{
		$this->createSelection();

		// Finding on fields from the mast_street table is handled here
		if (count($fields)) {
			foreach ($fields as $key=>$value) {
				if (in_array($key,$this->columns)) {
					$this->select->where("s.$key=?",$value);
				}
			}
		}

		// Add all the joins we've created to the select
		foreach ($this->getJoins($fields,'search') as $key=>$join) {
			$this->select->joinLeft(array($key=>$join['table']),$join['condition'],array());
		}

		$this->runSelection($order,$limit,$groupBy);
	}

	/**
	 * Adds the order, limit, and groupBy to the select, then sends the select to the database
	 *
	 * @param string $order
	 * @param string $limit
	 * @param string $groupBy
	 */
	private function runSelection($order,$limit=null,$groupBy=null)
	{
		$this->select->order("s.$order");
		if ($limit) {
			$this->select->limit($limit);
		}
		if ($groupBy) {
			$this->select->group($groupBy);
		}

		$this->populateList();
	}

	/**
	 * Function for handling any joins that are needed
	 *
	 * Finding on fields from other tables requires joining those tables.
	 * You can handle fields from other tables by adding the joins here
	 * If you add more joins you probably want to make sure that the
	 * above foreach only handles fields from the address_location table.
	 *
	 * Right now, find and search both do the same comparisons.  However, there
	 * may come a time when we want find to do exact matching and for search
	 * to do loose matching
	 *
	 * @param array $fields
	 * @param string $queryType find|search
	 */
	private function getJoins(array $fields=null,$queryType='find')
	{
		$joins = array();

		if ($fields) {
			if (isset($fields['street_name'])) {
				$joins['n'] = array('table'=>'mast_street_names',
									'condition'=>'s.street_id=n.street_id');
				if ($queryType=='find') {
					$this->select->where('n.street_name=?',$fields['street_name']);
				}
				else {
					$this->select->where('n.street_name like ?',"$fields[street_name]%");
				}
			}

			if (isset($fields['direction'])) {
				$joins['n'] = array('table'=>'mast_street_names',
									'condition'=>'s.street_id=n.street_id');
				$this->select->where('n.street_direction_code=?',$fields['direction']);
			}

			if (isset($fields['postDirection'])) {
				$joins['n'] = array('table'=>'mast_street_names',
									'condition'=>'s.street_id=n.street_id');
				$this->select->where('n.post_direction_suffix_code=?',
										$fields['postDirection']);
			}

			if (isset($fields['streetType'])) {
				$joins['n'] = array('table'=>'mast_street_names',
									'condition'=>'s.street_id=n.street_id');
				$this->select->where('n.street_type_suffix_code=?',$fields['streetType']);
			}
		}
		return $joins;
	}

	/**
	 * Hydrates all the MastStreet objects from a database result set
	 *
	 * This is a callback function, called from ZendDbResultIterator.  It is
	 * called once per row of the result.
	 *
	 * @param int $key The index of the result row to load
	 * @return MastStreet
	 */
	protected function loadResult($key)
	{
		return new Street($this->result[$key]);
	}
}
