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
		$this->columns = $this->zend_db->describeTable('mast_street');
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
	public function find($fields=null,$order='street_id',$limit=null,$groupBy=null)
	{
		$this->select->from(array('s'=>'mast_street'));

		if (isset($fields['direction'])) {
			$fields['street_direction_code'] = $fields['direction']->getCode();
			unset($fields['direction']);
		}
		if (isset($fields['postDirection'])) {
			$fields['post_direction_suffix_code'] = $fields['postDirection']->getCode();
			unset($fields['postDirection']);
		}

		// Finding on fields from the mast_street table is handled here
		if (count($fields)) {
			foreach ($fields as $key=>$value) {
				if (array_key_exists($key,$this->columns)) {
					$this->select->where("s.$key=?",$value);
				}
			}
		}

		if (isset($fields['street_name'])) {
			$this->select->joinLeft(array('n'=>'mast_street_names'),'s.street_id=n.street_id',array());
			$this->select->where('n.street_name like ?',"$fields[street_name]%");
		}


		// Finding on fields from other tables requires joining those tables.
		// You can handle fields from other tables by adding the joins here
		// If you add more joins you probably want to make sure that the
		// above foreach only handles fields from the mast_street table.

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
