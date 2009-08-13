<?php
/**
 * A collection class for LocationStatusChange objects
 *
 * This class creates a zend_db select statement.
 * ZendDbResultIterator handles iterating and paginating those results.
 * As the results are iterated over, ZendDbResultIterator will pass each desired
 * row back to this class's loadResult() which will be responsible for hydrating
 * each LocationStatusChange object
 *
 * Beyond the basic $fields handled, you will need to write your own handling
 * of whatever extra $fields you need
 *
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class LocationStatusChangeList extends ZendDbResultIterator
{
	private $columns;
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
		$this->columns = $this->zend_db->describeTable('mast_address_location_status');
		if (is_array($fields)) {
			$this->find($fields);
		}
	}

	/**
	 * Populates the collection
	 *
	 * @param array $fields
	 * @param string|array $order Multi-column sort should be given as an array
	 * @param int $limit
	 * @param string|array $groupBy Multi-column group by should be given as an array
	 */
	public function find($fields=null,$order='effective_start_date desc',$limit=null,$groupBy=null)
	{
		$this->select->from('mast_address_location_status');

		// Finding on fields from the mast_address_location_status table is handled here
		if (count($fields)) {
			foreach ($fields as $key=>$value) {
				if (array_key_exists($key,$this->columns)) {
					if ($value) {
						if ($value instanceof Date) {
							$value = $value->format('Y-m-d');
						}
						$this->select->where("$key=?",$value);
					}
					else {
						$this->select->where("$key is null");
					}
				}
			}
		}

		if (isset($fields['current'])) {
			$date = $fields['current']->format('Y-m-d');
			$this->select->where('effective_start_date<=?',$date);
			$this->select->where('effective_end_date is null or effective_end_date>=?',$date);
		}
		// Finding on fields from other tables requires joining those tables.
		// You can handle fields from other tables by adding the joins here
		// If you add more joins you probably want to make sure that the
		// above foreach only handles fields from the mast_address_location_status table.

		$this->select->order($order);
		if ($limit) {
			$this->select->limit($limit);
		}
		if ($groupBy) {
			$this->select->group($groupBy);
		}
		$this->populateList();
	}

	/**
	 * Hydrates all the LocationStatusChange objects from a database result set
	 *
	 * This is a callback function, called from ZendDbResultIterator.  It is
	 * called once per row of the result.
	 *
	 * @param int $key The index of the result row to load
	 * @return LocationStatusChange
	 */
	protected function loadResult($key)
	{
		return new LocationStatusChange($this->result[$key]);
	}
}