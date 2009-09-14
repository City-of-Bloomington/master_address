<?php
/**
 * A collection class for Location objects
 *
 * This class creates a zend_db select statement.
 * ZendDbResultIterator handles iterating and paginating those results.
 * As the results are iterated over, ZendDbResultIterator will pass each desired
 * row back to this class's loadResult() which will be responsible for hydrating
 * each Location object
 *
 * Beyond the basic $fields handled, you will need to write your own handling
 * of whatever extra $fields you need
 *
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class LocationList extends ZendDbResultIterator
{
	private $columns = array('location_id','location_type_id','street_address_id',
							'subunit_id','mailable_flag','livable_flag','common_name','active');
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
	public function find($fields=null,$order='location_id',$limit=null,$groupBy=null)
	{
		$this->select->from(array('l'=>'address_location'),
							array('location_id'));

		// If we pass in an address, we should parse the address string into the fields
		if (isset($fields['address'])) {
			$fields = AddressList::parseAddress($fields['address']);
			// We don't support searching for fractions right now
			if (isset($fields['fraction'])) {
				unset($fields['fraction']);
			}
			unset($fields['address']);
		}

		// Finding on fields from the address_location table is handled here
		if (count($fields)) {
			foreach ($fields as $key=>$value) {
				if (in_array($key,$this->columns)) {
					if ($value) {
						$this->select->where("l.$key=?",$value);
					}
					else {
						$this->select->where("l.$key is null");
					}
				}
			}
		}

		// Add any joins for extra fields to the select
		foreach ($this->getJoins($fields) as $key=>$join) {
			$this->select->joinLeft(array($key=>$join['table']),$join['condition'],array());
		}

		if ($order == 'street_number' && isset($fields['street_name'])) {
			$order = 'n.street_name,a.street_number';
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
	private function getJoins(array $fields,$queryType='find')
	{
		$joins = array();

		if (isset($fields['street_number'])) {
			$joins['a'] = array('table'=>'mast_address',
								'condition'=>'l.street_address_id=a.street_address_id');
			$this->select->where('a.street_number like ?',"$fields[street_number]%");
		}

		if (isset($fields['direction'])) {
			$joins['a'] = array('table'=>'mast_address',
								'condition'=>'l.street_address_id=a.street_address_id');
			$joins['s'] = array('table'=>'mast_street',
								'condition'=>'a.street_id=s.street_id');
			$this->select->where('s.street_direction_code=?',$fields['direction']->getCode());
		}

		if (isset($fields['postDirection'])) {
			$joins['a'] = array('table'=>'mast_address',
								'condition'=>'l.street_address_id=a.street_address_id');
			$joins['s'] = array('table'=>'mast_street',
								'condition'=>'a.street_id=s.street_id');
			$this->select->where('s.post_direction_suffix_code=?',
								$fields['postDirection']->getCode());
		}

		if (isset($fields['street_name'])) {
			$joins['a'] = array('table'=>'mast_address',
								'condition'=>'l.street_address_id=a.street_address_id');
			$joins['s'] = array('table'=>'mast_street',
								'condition'=>'a.street_id=s.street_id');
			$joins['n'] = array('table'=>'mast_street_names',
								'condition'=>'s.street_id=n.street_id');
			$this->select->where('n.street_name like ?',"$fields[street_name]%");

		}

		if (isset($fields['streetType'])) {
			$joins['a'] = array('table'=>'mast_address',
								'condition'=>'l.street_address_id=a.street_address_id');
			$joins['s'] = array('table'=>'mast_street',
								'condition'=>'a.street_id=s.street_id');
			$joins['n'] = array('table'=>'mast_street_names',
								'condition'=>'s.street_id=n.street_id');
			$this->select->where('n.street_type_suffix_code=?',$fields['streetType']->getCode());
		}

		if (isset($fields['subunitType'])) {
			$joins['a'] = array('table'=>'mast_address',
								'condition'=>'l.street_address_id=a.street_address_id');
			$joins['u'] = array('table'=>'mast_address_subunits',
								'condition'=>'a.street_address_id=u.street_address_id');
			$this->select->where('u.sudtype=?',$fields['subunitType']->getType());
		}

		return $joins;
	}

	/**
	 * Hydrates all the Location objects from a database result set
	 *
	 * This is a callback function, called from ZendDbResultIterator.  It is
	 * called once per row of the result.
	 *
	 * @param int $key The index of the result row to load
	 * @return Location
	 */
	protected function loadResult($key)
	{
		return new Location($this->result[$key]['location_id']);
	}
}
