<?php
/**
 * A collection class for Address objects
 *
 * This class creates a zend_db select statement.
 * ZendDbResultIterator handles iterating and paginating those results.
 * As the results are iterated over, ZendDbResultIterator will pass each desired
 * row back to this class's loadResult() which will be responsible for hydrating
 * each Address object
 *
 * Beyond the basic $fields handled, you will need to write your own handling
 * of whatever extra $fields you need
 *
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class AddressList extends ZendDbResultIterator
{
	private $columns;
	private static $directions = array();
	private static $streetTypes = array();
	private static $subunitTypes = array();
	private static $cities = array();

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
		$this->columns = $this->zend_db->describeTable('mast_address');

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
	public function find($fields=null,$order='street_number',$limit=null,$groupBy=null)
	{
		$this->select->from(array('a'=>'mast_address'));
		$this->select->joinLeft(array('trash'=>'mast_address_sanitation'),
								'a.street_address_id=trash.street_address_id',
								array('trash_pickup_day','recycle_week'));
		$this->select->joinLeft(array('status'=>'mast_address_latest_status'),
								'a.street_address_id=status.street_address_id',
								array('status_code','description'));

		// If we pass in an address, we should parse the address string into the fields
		if (isset($fields['address'])) {
			$fields = self::parseAddress($fields['address']);
			// We don't support searching for fractions right now
			if (isset($fields['fraction'])) {
				unset($fields['fraction']);
			}
			unset($fields['address']);
		}

		// Finding on fields from the mast_address table is handled here
		if (count($fields)) {
			foreach ($fields as $key=>$value) {
				if (array_key_exists($key,$this->columns)) {
					$this->select->where("a.$key=?",$value);
				}
			}
		}

		// Finding on fields from other tables requires joining those tables.
		// You can handle fields from other tables by adding the joins here
		// If you add more joins you probably want to make sure that the
		// above foreach only handles fields from the mast_address table.
		$joins = array();
		if (isset($fields['direction'])) {
			$joins['s'] = array('table'=>'mast_street',
								'condition'=>'a.street_id=s.street_id');
			$this->select->where('s.street_direction_code=?',$fields['direction']->getCode());
		}

		if (isset($fields['postDirection'])) {
			$joins['s'] = array('table'=>'mast_street',
								'condition'=>'a.street_id=s.street_id');
			$this->select->where('s.post_direction_suffix_code=?',
								$fields['postDirection']->getCode());
		}

		if (isset($fields['street_name'])) {
			$joins['s'] = array('table'=>'mast_street',
								'condition'=>'a.street_id=s.street_id');
			$joins['n'] = array('table'=>'mast_street_names',
								'condition'=>'s.street_id=n.street_id');
			$this->select->where('n.street_name like ?',"$fields[street_name]%");

			if ($order == 'street_number') {
				$order = 'n.street_name,a.street_number';
			}
		}

		if (isset($fields['streetType'])) {
			$joins['s'] = array('table'=>'mast_street',
								'condition'=>'a.street_id=s.street_id');
			$joins['n'] = array('table'=>'mast_street_names',
								'condition'=>'s.street_id=n.street_id');
			$this->select->where('n.street_type_suffix_code=?',$fields['streetType']->getCode());
		}

		if (isset($fields['subunitType'])) {
			$joins['u'] = array('table'=>'mast_address_subunits',
								'condition'=>'a.street_address_id=u.street_address_id');
			$this->select->where('u.sudtype=?',$fields['subunitType']->getType());
		}

		if (isset($fields['location_id'])) {
			$joins['l'] = array('table'=>'address_location',
								'condition'=>'a.street_address_id=l.street_address_id');
			$this->select->where('l.location_id=?',$fields['location_id']);
		}

		// Add all the joins we've created to the select
		foreach ($joins as $key=>$join) {
			$this->select->joinLeft(array($key=>$join['table']),$join['condition'],array());
		}

		$order = (substr($order,1,1) == '.') ? $order : "a.$order";
		$this->select->order($order);
		if ($limit) {
			$this->select->limit($limit);
		}
		if ($groupBy) {
			$this->select->group($groupBy);
		}
		$this->populateList();
	}

	public function search($fields=null,$order='street_number',$limit=null,$groupBy=null)
	{
		$this->select->from(array('a'=>'mast_address'));
		$this->select->joinLeft(array('trash'=>'mast_address_sanitation'),
										'a.street_address_id=trash.street_address_id',
										array('trash_pickup_day','recycle_week'));

		// If we pass in an address, we should parse the address string into the fields
		if (isset($fields['address'])) {
			$fields = self::parseAddress($fields['address']);
			// We don't support searching for fractions right now
			if (isset($fields['fraction'])) {
				unset($fields['fraction']);
			}
			unset($fields['address']);
		}

		// Finding on fields from the mast_address table is handled here
		if (count($fields)) {
			foreach ($fields as $key=>$value) {
				if (array_key_exists($key,$this->columns)) {
					$this->select->where("a.$key like ?","$value%");
				}
			}

			// Finding on fields from other tables requires joining those tables.
			// You can handle fields from other tables by adding the joins here
			// If you add more joins you probably want to make sure that the
			// above foreach only handles fields from the mast_address table.
			$joins = array();
			if (isset($fields['direction'])) {
				$joins['s'] = array('table'=>'mast_street',
									'condition'=>'a.street_id=s.street_id');
				$this->select->where('s.street_direction_code=?',$fields['direction']->getCode());
			}

			if (isset($fields['postDirection'])) {
				$joins['s'] = array('table'=>'mast_street',
									'condition'=>'a.street_id=s.street_id');
				$this->select->where('s.post_direction_suffix_code=?',
									$fields['postDirection']->getCode());
			}

			if (isset($fields['street_name'])) {
				$joins['s'] = array('table'=>'mast_street',
									'condition'=>'a.street_id=s.street_id');
				$joins['n'] = array('table'=>'mast_street_names',
									'condition'=>'s.street_id=n.street_id');
				$this->select->where('n.street_name like ?',"$fields[street_name]%");

				if ($order == 'street_number') {
					$order = 'n.street_name,a.street_number';
				}
			}

			if (isset($fields['streetType'])) {
				$joins['s'] = array('table'=>'mast_street',
									'condition'=>'a.street_id=s.street_id');
				$joins['n'] = array('table'=>'mast_street_names',
									'condition'=>'s.street_id=n.street_id');
				$this->select->where('n.street_type_suffix_code=?',$fields['streetType']->getCode());
			}

			if (isset($fields['subunitType'])) {
				$joins['u'] = array('table'=>'mast_address_subunits',
									'condition'=>'a.street_address_id=u.street_address_id');
				$this->select->where('u.sudtype=?',$fields['subunitType']->getType());
			}

			if (isset($fields['subdivision_id'])) {
				$joins['su'] = array('table'=>'mast_street_subdivision',
									'condition'=>'a.street_id=su.street_id');
				$this->select->where('su.subdivision_id=?',$fields['subdivision_id']);
			}

			if (isset($fields['location_id'])) {
				$joins['l'] = array('table'=>'address_location',
									'condition'=>'a.street_address_id=l.street_address_id');
				$this->select->where('l.location_id=?',$fields['location_id']);
			}
			// Add all the joins we've created to the select
			foreach ($joins as $key=>$join) {
				$this->select->joinLeft(array($key=>$join['table']),$join['condition'],array());
			}


			$order = (substr($order,1,1) == '.') ? $order : "a.$order";
			$this->select->order($order);
			if ($limit) {
				$this->select->limit($limit);
			}
			if ($groupBy) {
				$this->select->group($groupBy);
			}
			$this->populateList();
		}
	}

	/**
	 * Hydrates all the Address objects from a database result set
	 *
	 * This is a callback function, called from ZendDbResultIterator.  It is
	 * called once per row of the result.
	 *
	 * @param int $key The index of the result row to load
	 * @return Address
	 */
	protected function loadResult($key)
	{
		return new Address($this->result[$key]);
	}

	/**
	 * Creates an associative array for the parts of an address for a given string
	 *
	 * Only the parts of the address that are given in the string are returned
	 * Example: $string = "410 W 4th"
	 * Returns: array('street_number'=>'410',
	 *					'street_direction_code'=>'W',
	 *					'street_name'=>'4th'
	 *				)
	 *
	 * Example: $string = "401 N Morton St, Bloomington, IN"
	 * Returns: array('street_number'=>'401',
	 *					'street_direction_code'=>'N',
	 *					'street_name'=>'Morton',
	 *					'street_type_suffix_code'=>'St',
	 *					'city'=>'Bloomington',
	 *					'state'=>'IN'
	 *				)
	 *
	 *
	 * @param string $string
	 * @return array
	 */
	public static function parseAddress($string)
	{
		$output = array();

		//echo "Starting with $string\n";
		$address = preg_replace('/[^\w\s\/\-]/',' ',$string);
		$address = preg_replace('/\s+/',' ',$address);

		//echo "Looking for fractions: $address\n";
		if (preg_match("/(?<fraction>\d+\/\d+)/",$address,$matches)) {
			$output['fraction'] = trim($matches['fraction']);
			$address = preg_replace("/\d+\/\d+/",'',$address);
		}

		//echo "Looking for number: $address\n";
		$directionCodePattern = implode('|',self::getDirections());
		$numberPattern = "(?<number>\d+[\-\s]?(?:[^$directionCodePattern]\s)?)";
		if (preg_match("/^$numberPattern/i",$address,$matches)) {
			$output['street_number'] = trim($matches['number']);
			$address = preg_replace("/^$matches[number]/i",'',$address);
		}

		//echo "Looking for Zip: $address\n";
		$zipPattern = '(?<zip>\d{5})(\-(?<zipplus4>\d{4}))?';
		if (preg_match("/\s$zipPattern\s?$/i",$address,$matches)) {
			$output['zip'] = trim($matches['zip']);
			if (isset($matches['zipplus4']) && $matches['zipplus4']) {
				$output['zipplus4'] = trim($matches['zipplus4']);
			}
			$address = trim(preg_replace("/\s$zipPattern$/i",'',$address));
		}

		//echo "Looking for State: $address\n";
		if (preg_match("/\s(?<state>IN|INDIANA)\b/i",$address,$matches)) {
			$output['state'] = trim($matches['state']);
			$address = trim(preg_replace("/\s$matches[state]$/i",'',$address));
		}

		//echo "Looking for city: $address\n";
		$cityPattern = implode('|',self::getCities());
		if (preg_match("/\s(?<city>$cityPattern)$/i",$address,$matches)) {
			$output['city'] = trim($matches['city']);
			$address = trim(preg_replace("/\s$matches[city]$/i",'',$address));
		}

		//echo "Looking for subunit: $address\n";
		$subunitTypePattern = implode('|',array_merge(self::getSubunitTypes(),
														array_keys(self::getSubunitTypes())));
		$subunitPattern = "(?<subunitType>$subunitTypePattern)(\-|\s)?(?<subunitIdentifier>\w+)";
		if (preg_match("/\s(?<subunit>$subunitPattern)$/i",$address,$matches)) {
			try {
				$output['subunitType'] = new SubunitType($matches['subunitType']);
				$output['subunitIdentifier'] = $matches['subunitIdentifier'];
				$address = trim(preg_replace("/\s$matches[subunit]$/i",'',$address));
			}
			catch (Exception $e) {
				// Just ignore anything that's not a known type
			}
		}

		//echo "Looking for Street Name: $address\n";
		$fullDirectionPattern = implode('|',array_merge(self::getDirections(),
														array_keys(self::getDirections())));
		$streetTypePattern = implode('|',array_merge(self::getStreetTypes(),
													array_keys(self::getStreetTypes())));
		$streetPattern = "
		(
			(?<dir>$fullDirectionPattern)\s(?<type>$streetTypePattern)\b
			|
			((?<direction>$fullDirectionPattern)\s)?
			(
				(?<name>[\w\s]+)
				(\s(?<streetType>$streetTypePattern)\b)
				(\s(?<postdirection>$fullDirectionPattern)\b)?
				|
				(?<streetName>[\w\s]+)
				(\s(?<postdir>$fullDirectionPattern))\b
				|
				(?<street>[\w\s]+)
				(\s(?<stype>$streetTypePattern)\b)?
				(\s(?<pdir>$fullDirectionPattern)\b)?
			)
		)
		";
		preg_match("/$streetPattern/ix",$address,$matches);
		foreach ($matches as $key=>$value) {
			if (!is_int($key) && trim($value)) {
				// The regular expression for street names required some duplication.
				// Since you cannot use the same named subpattern more than once,
				// we came up with different names for the same things in the regex.
				// We need to convert any of those names back into the real name
				switch ($key) {
					case 'dir':
					case 'direction':
						try {
							$output['direction'] = new Direction($value);
						}
						catch (Exception $e) {
							// Just ignore anything that's not a known direction
						}
						break;

					case 'type':
					case 'streetType':
					case 'stype':
						try {
							$output['streetType'] = new StreetType($value);
						}
						catch (Exception $e) {
							// Just ignore anything that's not a known street type
						}
						break;

					case 'name':
					case 'street':
					case 'streetName':
						$output['street_name'] = $value;
						break;

					case 'postdirection':
					case 'postdir':
					case 'pdir':
						try {
							$output['postDirection'] = new Direction($value);
						}
						catch (Exception $e) {
							// Just ignore anything that's not a known direction
						}
						break;
				}
			}
		}

		return $output;
	}

	private static function getDirections()
	{
		if (!count(self::$directions)) {
			$list = new DirectionList();
			$list->find();
			foreach ($list as $direction) {
				self::$directions[$direction->getDescription()] = $direction->getCode();
			}
		}
		return self::$directions;
	}

	private static function getStreetTypes()
	{
		if (!count(self::$streetTypes)) {
			$list = new StreetTypeList();
			$list->find();
			foreach ($list as $streetType) {
				self::$streetTypes[$streetType->getDescription()] = $streetType->getCode();
			}
		}
		return self::$streetTypes;
	}

	private static function getSubunitTypes()
	{
		if (!count(self::$subunitTypes)) {
			$list = new SubunitTypeList();
			$list->find();
			foreach ($list as $subunitType) {
				self::$subunitTypes[$subunitType->getType()] = $subunitType->getDescription();
			}
		}
		return self::$subunitTypes;
	}

	private static function getCities()
	{
		if (!count(self::$cities)) {
			$zend_db = Database::getConnection();
			$query = $zend_db->query('select distinct city from mast_address');
			$result = $query->fetchAll();
			foreach ($result as $row) {
				self::$cities[] = $row['city'];
			}
		}
		return self::$cities;
	}
}
