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
 * @copyright 2009-2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class AddressList extends ZendDbResultIterator
{
	private $columns = [
		'street_address_id','address_type',
		'street_id', 'street_number', 'street_number_suffix',
		'jurisdiction_id','gov_jur_id','township_id',
		'section','quarter_section','subdivision_id',
		'plat_id','plat_lot_number','street_address_2',
		'city','state','zip','zipplus4',
		'state_plane_x_coordinate', 'state_plane_y_coordinate','usng_coordinate',
		'notes'
	];

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
	 *
	 * @param array $fields
	 */
	private function createSelection($fields=null)
	{
		if (isset($fields['latitude']) && isset($fields['longitude'])
			&& is_numeric($fields['latitude']) && is_numeric($fields['longitude'])) {
			$fields['latitude'] = (float)$fields['latitude'];
			$fields['longitude'] = (float)$fields['longitude'];
			$this->select->distinct()->from(
				array('a'=>'mast_address'),
				array(
					'a.*',
					'distance'=>"(sqrt(power(($fields[latitude] - latitude),2) + power(($fields[longitude] - longitude),2)))"
				)
			);
		}
		else {
			$this->select->distinct()->from(array('a'=>'mast_address'));
		}
		$this->select->joinLeft(array('trash'=>'mast_address_sanitation'),
								'a.street_address_id=trash.street_address_id',
								array('trash_pickup_day','recycle_week'));
		$this->select->joinLeft(array('status'=>'mast_address_latest_status'),
								'a.street_address_id=status.street_address_id',
								array('status_code','description'));
	}

	/**
	 * Populates the collection using exact matching
	 *
	 * @param array $fields
	 * @param string|array $order Multi-column sort should be given as an array
	 * @param int $limit
	 * @param string|array $groupBy Multi-column group by should be given as an array
	 */
	public function find($fields=null,$order='street_number',$limit=null,$groupBy=null)
	{
		$this->createSelection($fields);

		// If we pass in an address, we should parse the address string into the fields
		if (isset($fields['address'])) {
			$fields = self::parseAddress($fields['address']);
			// Add Fractions onto the street_number
			if (isset($fields['fraction'])) {
				$fields['street_number'].= ' '.$fields['fraction'];
				unset($fields['fraction']);
			}
			unset($fields['address']);
		}

		if (count($fields)) {
			foreach ($fields as $key=>$value) {
				if (is_string($value)) {
					$value = trim($value);
					$fields[$key] = $value;
				}
				if (in_array($key,$this->columns)) {
					$this->select->where("a.$key=?",$value);
				}
			}

			// Add any joins for extra fields to the select
			$joins = $this->getJoins($fields,'search');
			foreach ($joins as $key=>$join) {
				$this->select->joinLeft(array($key=>$join['table']),$join['condition'],array());
			}
		}

		$this->runSelection($order,$limit,$groupBy);
	}

	/**
	 * Populates the collection using loose matching
	 *
	 * @param array $fields
	 * @param string|array $order Multi-column sort should be given as an array
	 * @param int $limit
	 * @param string|array $groupBy Multi-column group by should be given as an array
	 */
	public function search($fields=null,$order='street_number',$limit=null,$groupBy=null)
	{
		$this->createSelection($fields);

		// If we pass in an address, we should parse the address string into the fields
		if (isset($fields['address'])) {
			$fields = self::parseAddress($fields['address']);
			// Add Fractions onto the street_number
			if (isset($fields['fraction'])) {
				$fields['street_number'] = isset($fields['street_number'])
					? "$fields[street_number] $fields[fraction]"
					: $fields['fraction'];
				unset($fields['fraction']);
			}
			unset($fields['address']);
		}

		// Finding on fields from the mast_address table is handled here
		if (count($fields)) {
			foreach ($fields as $key=>$value) {
				if (is_string($value)) {
					$value = trim($value);
					$fields[$key] = $value;
				}
				if (in_array($key,$this->columns)) {
					$this->select->where("a.$key like ?","$value%");
				}
			}

			// Add all the joins we've created to the select
			$joins = $this->getJoins($fields,'search');
			foreach ($joins as $key=>$join) {
				$this->select->joinLeft(array($key=>$join['table']),$join['condition'],array());
			}

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
		if ($order != 'distance') {
			$order = substr($order,0,2)!='a.' ? 'a.'.$order : $order;
		}

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

		if (isset($fields['direction'])) {
			if (!$fields['direction'] instanceof Direction) {
				try {
					$fields['direction'] = new Direction($fields['direction']);
				}
				catch (Exception $e) {
				}
			}
			if ($fields['direction'] instanceof Direction) {
				$joins['s'] = array('table'=>'mast_street',
									'condition'=>'a.street_id=s.street_id');
				$joins['n'] = array('table'=>'mast_street_names',
									'condition'=>'s.street_id=n.street_id');
				$this->select->where('n.street_direction_code=?',$fields['direction']->getCode());
			}
		}

		if (isset($fields['postDirection'])) {
			if (!$fields['postDirection'] instanceof Direction) {
				try {
					$fields['postDirection'] = new Direction($fields['postDirection']);
				}
				catch (Exception $e) {
				}
			}
			if ($fields['postDirection'] instanceof Direction) {
				$joins['s'] = array('table'=>'mast_street',
									'condition'=>'a.street_id=s.street_id');
				$joins['n'] = array('table'=>'mast_street_names',
									'condition'=>'s.street_id=n.street_id');
				$this->select->where('n.post_direction_suffix_code=?',
									$fields['postDirection']->getCode());
			}
		}

		if (isset($fields['street_name'])) {
			$joins['s'] = array('table'=>'mast_street',
								'condition'=>'a.street_id=s.street_id');
			$joins['n'] = array('table'=>'mast_street_names',
								'condition'=>'s.street_id=n.street_id');
			if ($queryType=='find') {
				$this->select->where('n.street_name=?',$fields['street_name']);
			}
			else {
				$this->select->where('n.street_name like ?',"%$fields[street_name]%");
			}
		}

		if (isset($fields['streetType'])) {
			if (!$fields['streetType'] instanceof StreetType) {
				try {
					$fields['streetType'] = new StreetType($fields['streetType']);
				}
				catch (Exception $e) {
				}
			}
			if ($fields['streetType'] instanceof StreetType) {
				$joins['s'] = array('table'=>'mast_street',
									'condition'=>'a.street_id=s.street_id');
				$joins['n'] = array('table'=>'mast_street_names',
									'condition'=>'s.street_id=n.street_id');
				$this->select->where('n.street_type_suffix_code=?',$fields['streetType']->getCode());
			}
		}

		if (isset($fields['subunitType'])) {
			// If they're doing a search, and they're looking for a particular subunit,
			// just ignore whatever they typed for the subunit type.
			// They may searched for APT 1 and the address only has SUITES.
			// If there's a subunit 1, we should return it no matter what type of subunit it is
			if ($queryType=='find' || !empty($fields['subunitIdentifier'])) {
				if ($fields['subunitType'] instanceof SubunitType) {
					try {
						$fields['subunitType'] = new SubunitType($fields['subunitType']);
					}
					catch (Exception $e) {
					}
				}
				if ($fields['subunitType'] instanceof SubunitType) {
					$joins['u'] = array('table'=>'mast_address_subunits',
										'condition'=>'a.street_address_id=u.street_address_id');
					$this->select->where('u.sudtype=?',$fields['subunitType']->getType());
				}
			}
		}
		if (isset($fields['subunitIdentifier'])) {
			$joins['u'] = array('table'=>'mast_address_subunits',
								'condition'=>'a.street_address_id=u.street_address_id');
			$this->select->where('u.subunit_identifier=?',$fields['subunitIdentifier']);
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

		if (isset($fields['status'])) {
			$status = ($fields['status'] instanceof AddressStatus)
					? $fields['status']
					: new AddressStatus($fields['status']);
			$this->select->where('status.status_code=?',$status->getCode());
		}

		return $joins;
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
	 * The strategy here is to match parts of the address piecemeal.
	 * As each part is matched, it is removed from the input string;
	 * this reduces the complexity for subsequent matches.
	 * Also, this is generally an outside-in approach.  We try matching
	 * parts at the beginning and end of the string first.  Then, we
	 * work our way towards the middle of the string.
	 *
	 * @param string $string
	 * @return array
	 */
	public static function parseAddress($string,$parseType='address')
	{
		$output = array();

		//echo "Starting with |$string|\n";
		$address = preg_replace('/[^a-z0-9\-\s\/]/i', '', $string);
		$address = preg_replace('/\s+/', ' ', $address);

		if ($parseType=='address') {
			//echo "Looking for number: |$address|\n";
			$fraction = '\d\/\d';
			$directionCodePattern = implode('', self::getDirections());
			$numberPattern = "(?<prefix>$fraction\s|[A-Z]\s)?(?<number>\d+)(?<suffix>\s$fraction\s|\-\d+\s|\s?[A-Z]\s)?(?<direction>[$directionCodePattern]\s)?";

			if (preg_match("/^$numberPattern/i", $address, $matches)) {

				if (!empty($matches['prefix'])) { $output['street_number_prefix'] = trim($matches['prefix']); }
				$output['street_number'] = trim($matches['number']);

				if (!empty($matches['direction'])) {
					$output['direction'] = trim($matches['direction']);
					if (!empty($matches['suffix'])) {
						$output['street_number_suffix'] = trim($matches['suffix']);
					}
				}
				elseif (!empty($matches['suffix'])) {
					$s = strtoupper(trim($matches['suffix']));
					if (in_array($s, self::getDirections())) {
						$output['direction'] = $s;
					}
					else {
						$output['street_number_suffix'] = $s;
					}
				}
				$address = trim(preg_replace("#^$matches[0]#i",'',$address));
			}

			//echo "Looking for Zip: |$address|\n";
			$zipPattern = '(?<zip>\d{5})(\-(?<zipplus4>\d{4}))?';
			if (preg_match("/\s$zipPattern\s?$/i",$address,$matches)) {
				$output['zip'] = trim($matches['zip']);
				if (isset($matches['zipplus4']) && $matches['zipplus4']) {
					$output['zipplus4'] = trim($matches['zipplus4']);
				}
				$address = trim(preg_replace("/\s$zipPattern$/i",'',$address));
			}

			//echo "Looking for State: |$address|\n";
			if (preg_match("/\s(?<state>IN)\b/i",$address,$matches)) {
				$output['state'] = trim($matches['state']);
				$address = trim(preg_replace("/\s$matches[state]$/i",'',$address));
			}

			//echo "Looking for city: |$address|\n";
			$cityPattern = implode('|',self::getCities());
			if (preg_match("/\s(?<city>$cityPattern)$/i",$address,$matches)) {
				$output['city'] = trim($matches['city']);
				$address = trim(preg_replace("/\s$matches[city]$/i",'',$address));
			}

			//echo "Looking for subunit: |$address|\n";
			$subunitTypePattern = implode('|',array_merge(self::getSubunitTypes(),
															array_keys(self::getSubunitTypes())));
			$subunitPattern = "(?<subunitType>$subunitTypePattern)(\-|\s)?(?<subunitIdentifier>\w+)";
			if (preg_match("/\s(?<subunit>$subunitPattern)$/i",$address,$matches)) {
				try {
                    $type = new SubunitType($matches['subunitType']);
					$output['subunitType'] = $type->getType();
					$output['subunitIdentifier'] = $matches['subunitIdentifier'];
					$address = trim(preg_replace("/\s$matches[subunit]$/i",'',$address));
				}
				catch (Exception $e) {
					// Just ignore anything that's not a known type
				}
			}
		}

		//echo "Looking for Street Name: |$address|\n";
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
				$
				|
				(?<streetName>[\w\s]+)
				(\s(?<postdir>$fullDirectionPattern))\b
				$
				|
				(?<street>[\w\s]+)
				(\s(?<stype>$streetTypePattern)\b)?
				(\s(?<pdir>$fullDirectionPattern)\b)?
				$
			)
		)
		";
		preg_match("/$streetPattern/ix",$address,$matches);
		//print_r($matches);
		foreach ($matches as $key=>$value) {
			if (!is_int($key) && trim($value)) {
				// The regular expression for street names required some duplication.
				// Since you cannot use the same named subpattern more than once,
				// we came up with different names for the same things in the regex.
				// We need to convert any of those names back into the real name
				switch ($key) {
					case 'dir':
					case 'direction':
                        $value = strtoupper($value);
                        if (            in_array($value, self::$directions)) { $output['direction'] = $value; }
                        elseif (array_key_exists($value, self::$directions)) { $output['direction'] = self::$directions[$value]; }
                        // Just ignore anything that's not a known direction
						break;

					case 'type':
					case 'streetType':
					case 'stype':
                        $value = strtoupper($value);
                        if (            in_array($value, self::$streetTypes)) { $output['streetType'] = $value; }
                        elseif (array_key_exists($value, self::$streetTypes)) { $output['streetType'] = self::$streetTypes[$value]; }
                        // Just ignore anything that's not a known street type
						break;

					case 'name':
					case 'street':
					case 'streetName':
						$output['street_name'] = $value;
						break;

					case 'postdirection':
					case 'postdir':
					case 'pdir':
                        $value = strtoupper($value);
                        if (            in_array($value, self::$directions)) { $output['postDirection'] = $value; }
                        elseif (array_key_exists($value, self::$directions)) { $output['postDirection'] = self::$directions[$value]; }
                        // Just ignore anything that's not a known direction
						break;
				}
			}
		}

		// Sanity Checking
		if (!isset($output['street_name']) && isset($output['streetType'])) {
			$output['street_name'] = $output['streetType'];
			unset($output['streetType']);
		}
		if (!empty($output['street_number_suffix']) && empty($output['street_name'])) {
			$output['street_name'] = $output['street_number_suffix'];
			unset($output['street_number_suffix']);
		}
		return $output;
	}

    /**
     * Returns street directions in use by the system
     *
     * !IMPORTANT
     * For performance, this function has been cached in code.
     *
     * The directions are being prepopulated, in the static $directions variable.
     * If there is a direction that is not being parsed in the
     * address parser, you might need to regenerate the list, and
     * update the $directions variable
     *
     * The database query has been left here for reference.
     *
     * @return array
     */
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
    private static $directions = [
        'NORTH' => 'N',
        'EAST'  => 'E',
        'SOUTH' => 'S',
        'WEST'  => 'W'
    ];

	/**
     * Returns street types in use by the system
     *
     * !IMPORTANT
     * For performance, this function has been cached in code.
     * Doing a select distinct across hundreds of thousands of rows was
     * too slow for a web-service backing.
     *
     * The street types are being prepopulated, in the static $streetTypes variable.
     * If there is a street type that is not being parsed in the
     * address parser, you might need to regenerate the list, and
     * update the $streetTypes variable
     *
     * The database query has been left here for reference.
     *
	 * @return array
	 */
	private static function getStreetTypes()
	{
		if (!count(self::$streetTypes)) {
			$zend_db = Database::getConnection();
			$sql = "select distinct n.street_type_suffix_code,t.description,t.id
					from mast_street_names n
					left join mast_street_type_suffix_master t on n.street_type_suffix_code=t.suffix_code
					where street_type_suffix_code is not null";
			$result = $zend_db->fetchAll($sql);
			foreach ($result as $row) {
				self::$streetTypes[$row['description']] = $row['street_type_suffix_code'];
			}
		}
		return self::$streetTypes;
	}
    private static $streetTypes = [
        'WAY'       => 'WAY',
        'PARKWAY'   => 'PKWY',
        'GROVE'     => 'GRV',
        'PIKE'      => 'PIKE',
        'POINT'     => 'PT',
        'MEWS'      => 'MEWS',
        'BEND'      => 'BND',
        'AVENUE'    => 'AVE',
        'BOULEVARD' => 'BLVD',
        'FORK'      => 'FRK',
        'CIRCLE'    => 'CIR',
        'ROW'       => 'ROW',
        'TERRACE'   => 'TER',
        'DRIVE'     => 'DR',
        'ESTATES'   => 'ESTS',
        'TURN'      => 'TURN',
        'TRAIL'     => 'TRL',
        'LANE'      => 'LN',
        'BOW'       => 'BOW',
        'CROSSING'  => 'XING',
        'VALLEY'    => 'VLY',
        'ROAD'      => 'RD',
        'COURT'     => 'CT',
        'PLACE'     => 'PL',
        'GLEN'      => 'GLN',
        'KNOLL'     => 'KNL',
        'SQUARE'    => 'SQ',
        'VIEW'      => 'VW',
        'MALL'      => 'MALL',
        'RUN'       => 'RUN',
        'PATH'      => 'PATH',
        'CLIFFS'    => 'CLFS',
        'RIDGE'     => 'RDG',
        'STREET'    => 'ST',
        'CREST'     => 'CRST',
        'TRACE'     => 'TRCE',
        'ALLEY'     => 'ALY',
        'CHASE'     => 'CHASE',
        'HILL'      => 'HL'
    ];

    /**
     * Returns cities in use by the system
     *
     * !IMPORTANT
     * For performance, this function has been cached in code.
     * Doing a select distinct across hundreds of thousands of rows was
     * too slow for a web-service backing.
     *
     * The types are being prepopulated, in the static $subunitTypes variable.
     * If there is a type that is not being parsed in the
     * address parser, you might need to regenerate the list, and
     * update the $subunitTypes variable
     *
     * The database query has been left here for reference.
     *
     * @return array
     */
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
    private static $subunitTypes = [
        'APT'     => 'APARTMENT',
        'BSMT'    => 'BASEMENT',
        'BLDG'    => 'BUILDING ',
        'DEPT'    => 'DEPARTMENT',
        'FL'      => 'FLOOR',
        'FRNT'    => 'FRONT',
        'HNGR'    => 'HANGER',
        'KEY'     => 'KEY',
        'LBBY'    => 'LOBBY',
        'LOT'     => 'LOT',
        'LOWR'    => 'LOWER',
        'OFC'     => 'OFFICE',
        'PH'      => 'PENTHOUSE',
        'PIER'    => 'PIER',
        'REAR'    => 'REAR',
        'RM'      => 'ROOM',
        'SIDE'    => 'SIDE',
        'SLIP'    => 'SLIP',
        'SPC'     => 'SPACE',
        'STOP'    => 'STOP',
        'STE'     => 'SUITE',
        'TRLR'    => 'TRAILER',
        'UNIT'    => 'UNIT',
        'UPPR'    => 'UPPER',
        'UNKNOWN' => 'UNKNOWN'
    ];

	/**
	 * Returns cities in use by the system
	 *
	 * !IMPORTANT
	 * For performance, this function has been cached in code.
	 * Doing a select distinct across hundreds of thousands of rows was
	 * too slow for a web-service backing.
	 *
	 * The cities are being prepopulated, in the static $cities variable.
	 * If there is a city that is not being parsed in the
	 * address parser, you might need to regenerate the list, and
	 * update the $cities variable
	 *
	 * The database query has been left here for reference.
	 *
	 * @return array
	 */
	public static function getCities()
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
    private static $cities = [
        'Bedford',
        'Harrodsburg',
        'Stanford',
        'Bloomington',
        'Springville',
        'Martinsville',
        'Unionville',
        'Gosport',
        'Spencer',
        'Stinesville',
        'Ellettsville',
        'Nashville',
        'Clear Creek',
        'Heltonville',
        'Smithville'
    ];
}
