<?php
/**
 * A collection class for SubdivisionMaster objects
 *
 * This class creates a zend_db select statement.
 * ZendDbResultIterator handles iterating and paginating those results.
 * As the results are iterated over, ZendDbResultIterator will pass each desired
 * row back to this class's loadResult() which will be responsible for hydrating
 * each SubdivisionMaster object
 *
 * Beyond the basic $fields handled, you will need to write your own handling
 * of whatever extra $fields you need
 */
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class SubdivisionList extends ZendDbResultIterator
{
  	private $columns;
	/**
	 * Creates a basic select statement for the collection.
	 * Populates the collection if you pass in $fields
	 *
	 * @param array $fields
	 */
	public function __construct($fields=null)
	{

		parent::__construct();
		$this->columns = $this->zend_db->describeTable('subdivision_master');
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
	public function find($fields=null,$order='subdivision_id',$limit=null,$groupBy=null)
	{
		$this->select->from('subdivision_master');
		
		// Finding on fields from the subdivision_master table is handled here
		if (count($fields)) {
			foreach ($fields as $key=>$value) {
				$this->select->where("$key=?",$value);
			}
		}

		// Finding on fields from other tables requires joining those tables.
		// You can handle fields from other tables by adding the joins here
		// If you add more joins you probably want to make sure that the
		// above foreach only handles fields from the subdivision_master table.

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
	 * Search the collection
	 *
	 * @param array $fields
	 * @param string|array $order Multi-column sort should be given as an array
	 * @param int $limit
	 * @param string|array $groupBy Multi-column group by should be given as an array
	 */
	public function search($fields=null,$order='subdivision_id',$limit=null,$groupBy=null)
	{
		$this->select->from(array('s'=>'subdivision_master'));
		// Finding on fields from the subdivision_master table is handled here
		if (count($fields)) {
			foreach ($fields as $key=>$value) {
			    if (array_key_exists($key,$this->columns)) {
				    $this->select->where("s.$key=?",$value);
			    }
			}
		}
		// Finding on fields from other tables requires joining those tables.
		// You can handle fields from other tables by adding the joins here
		// If you add more joins you probably want to make sure that the
		// above foreach only handles fields from the subdivision_master table.
		$joins = array();
		
		if (isset($fields['name'])) {
			$joins['n'] = array('table'=>'subdivision_names','condition'=>'n.subdivision_id=s.subdivision_id');
			$this->select->where('n.name like ?',"%{$fields['name']}%");
		}
		if (isset($fields['phase'])) {
			$joins['n'] = array('table'=>'subdivision_names','condition'=>'n.subdivision_id=s.subdivision_id');
			$this->select->where('n.phase = ?',$fields['phase']);
		}
		if (isset($fields['status'])) {
			$joins['n'] = array('table'=>'subdivision_names','condition'=>'n.subdivision_id=s.subdivision_id');
			$this->select->where('n.status = ?',$fields['status']);
		}
		foreach ($joins as $key=>$join) {
			$this->select->joinLeft(array($key=>$join['table']),$join['condition']);
		}
		$this->select->order("s.$order");
		if ($limit) {
			$this->select->limit($limit);
		}
		if ($groupBy) {
			$this->select->group($groupBy);
		}
		// echo $this->select->__toString();
		
		$this->populateList();
	}
	
	/**
	 * Hydrates all the SubdivisionMaster objects from a database result set
	 *
	 * This is a callback function, called from ZendDbResultIterator.  It is
	 * called once per row of the result.
	 *
	 * @param int $key The index of the result row to load
	 * @return SubdivisionMaster
	 */
	protected function loadResult($key)
	{
		return new Subdivision($this->result[$key]);
	}
}
