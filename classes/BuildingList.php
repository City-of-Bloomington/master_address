<?php
/**
 * A collection class for Building objects
 *
 * This class creates a zend_db select statement.
 * ZendDbResultIterator handles iterating and paginating those results.
 * As the results are iterated over, ZendDbResultIterator will pass each desired
 * row back to this class's loadResult() which will be responsible for hydrating
 * each Building object
 *
 * Beyond the basic $fields handled, you will need to write your own handling
 * of whatever extra $fields you need
 */
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class BuildingList extends ZendDbResultIterator
{
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
	 * Populates the collection
	 *
	 * @param array $fields
	 * @param string|array $order Multi-column sort should be given as an array
	 * @param int $limit
	 * @param string|array $groupBy Multi-column group by should be given as an array
	 */
	public function find($fields=null,$order='building_id',$limit=null,$groupBy=null)
	{
		$this->select->from('buildings');

		// Finding on fields from the buildings table is handled here
		if (count($fields)) {
		   foreach ($fields as $key=>$value) {
			  if($key == 'building_name')
				$this->select->where("$key like ?",$value);
			  else
				$this->select->where("$key = ?",$value);
			}
		}

		// Finding on fields from other tables requires joining those tables.
		// You can handle fields from other tables by adding the joins here
		// If you add more joins you probably want to make sure that the
		// above foreach only handles fields from the buildings table.

		$this->select->order($order);
		if ($limit) {
			$this->select->limit($limit);
		}
		if ($groupBy) {
			$this->select->group($groupBy);
		}
		$this->populateList();
	}

   	public function search($fields=null,$order='building_id',$limit=null,$groupBy=null)
	{
		$this->select->from('buildings');

		// Finding on fields from the buildings table is handled here
		if (count($fields)) {
		   foreach ($fields as $key=>$value) {
			  if($key == 'building_name')
				$this->select->where("$key like ?",$value);
			  else
				$this->select->where("$key = ?",$value);
			}
		}

		// Finding on fields from other tables requires joining those tables.
		// You can handle fields from other tables by adding the joins here
		// If you add more joins you probably want to make sure that the
		// above foreach only handles fields from the buildings table.

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
	 * Hydrates all the Building objects from a database result set
	 *
	 * This is a callback function, called from ZendDbResultIterator.  It is
	 * called once per row of the result.
	 *
	 * @param int $key The index of the result row to load
	 * @return Building
	 */
	protected function loadResult($key)
	{
		return new Building($this->result[$key]);
	}
}
