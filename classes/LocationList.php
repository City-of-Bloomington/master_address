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
 * @copyright 2009-2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class LocationList extends ZendDbResultIterator
{
	/**
	 * Fields from the location table
	 */
	private $columns = ['location_id','location_type_id','street_address_id',
						'subunit_id','mailable_flag','livable_flag','active'];

	/**
	 * Fields from other tables that we can search on
	 *
	 * The key   is the fieldname returned by the AddressList::parseAddress()
	 * The value is the actual database fieldname
	 */
	private static $joinableFields = [
        'street_number_prefix' => 'street_number_prefix',
        'street_number'        => 'street_number',
        'street_number_suffix' => 'street_number_suffix',
        'direction'            => 'street_direction_code',
        'postDirection'        => 'post_direction_suffix_code',
        'street_name'          => 'street_name',
        'streetType'           => 'street_type_suffix_code',
        'subunitType'          => 'sudtype',
        'subunitIdentifier'    => 'subunit_identifier'
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
	public function __construct($fields=null, $itemsPerPage=null, $currentPage=null)
	{
		parent::__construct($itemsPerPage, $currentPage);

		if (is_array($fields)) { $this->find($fields); }
	}

	/**
	 * Populates the collection
	 *
	 * @param array $fields
	 * @param string|array $order Multi-column sort should be given as an array
	 * @param int $limit
	 * @param string|array $groupBy Multi-column group by should be given as an array
	 */
	public function find($fields=null, $order='location_id', $limit=null, $groupBy=null)
	{
		$this->select->distinct()->from(['l'=>'address_location'], ['location_id']);

		// If we pass in an address, we should parse the address string into the fields
		if (isset($fields['address'])) {
			$fields = AddressList::parseAddress($fields['address']);
			unset($fields['address']);
		}

        foreach ($this->getJoins($fields) as $key=>$join) {
            $this->select->joinLeft([$key=>$join['table']], $join['condition'], []);
        }

		if (count($fields)) {
			foreach ($fields as $key=>$value) {
				if (in_array($key, $this->columns)) {
                    $value
                        ? $this->select->where("l.$key=?",$value)
                        : $this->select->where("l.$key is null");
				}
				elseif (in_array($key, array_keys(self::$joinableFields))) {
                    $f = self::$joinableFields[$key];
                    $this->select->where("$f=?", $value);
				}
			}
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
	 */
	private function getJoins(array $fields)
	{
        $addressJoin = ['table'=>'mast_address',          'condition'=>'l.street_address_id=a.street_address_id'];
        $streetsJoin = ['table'=>'mast_street',           'condition'=>'a.street_id=s.street_id'];
        $namesJoin   = ['table'=>'mast_street_names',     'condition'=>'s.street_id=n.street_id'];
        $subunitJoin = ['table'=>'mast_address_subunits', 'condition'=>'a.street_address_id=u.street_address_id'];

		$joins = array();

		if (isset($fields['street_number'])) {
			$joins['a'] = $addressJoin;
		}

		if (isset($fields['direction'])) {
			$joins['a'] = $addressJoin;
			$joins['s'] = $streetsJoin;
		}

		if (isset($fields['postDirection'])) {
			$joins['a'] = $addressJoin;
			$joins['s'] = $streetsJoin;
		}

		if (isset($fields['street_name'])) {
			$joins['a'] = $addressJoin;
			$joins['s'] = $streetsJoin;
			$joins['n'] = $namesJoin;
		}

		if (isset($fields['streetType'])) {
			$joins['a'] = $addressJoin;
			$joins['s'] = $streetsJoin;
			$joins['n'] = $namesJoin;
		}

		if (isset($fields['subunitType']) || isset($fields['subunitIdentifier'])) {
            echo "Joining subunit table\n";
			$joins['a'] = $addressJoin;
			$joins['u'] = $subunitJoin;
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

	/**
	 * Returns location data for the best, current location based on an address string
	 *
	 * If the addressString is for an address that has been changed, the
	 * location data for the new address will be returned
	 *
	 * If the addressString is not a valid address, this will return null
	 *
	 * @param string $address
	 * @return array
	 */
	public static function verify($address)
	{
        $search = AddressList::parseAddress($address, 'address');

        // If you have a subunitType, you must have a subunitIdentifier
        if (isset($search['subunitType']) && !isset($search['subunitIdentifier'])) {
            unset($search['subunitType']);
        }

        if (!empty($search['street_number']) && !empty($search['street_name'])) {
            $options    = [];
            $bindValues = [];
            foreach ($search as $key=>$value) {
                if (in_array($key, array_keys(self::$joinableFields))) {
                    $f = self::$joinableFields[$key];
                    $options[]        = "$f=:$key";
                    $bindValues[$key] = $value;
                }
            }
            // If the user gave us a subunit, the previous foreach will have added the
            // subunit to the query.  However, if they did not give us a subunit,
            // then we need to look for locations that do not have a subunit
            if (isset($search['subunitIdentifier'])) {
                $subunitJoin = 'left join eng.mast_address_subunits u on a.street_address_id=u.street_address_id';
            }
            else {
                $options[] = "l.subunit_id is null";
            }

            if (count($options)) {
                $options = implode(' and ', $options);

                // Make sure to left join the subunits table.
                // Not all addresses have entries in the subunit table
                $sql = "select ol.* from eng.address_location ol where ol.location_id in (
                            select l.location_id
                            from eng.address_location      l
                            join eng.mast_address          a on l.street_address_id=a.street_address_id
                            join eng.mast_street           s on a.street_id=s.street_id
                            join eng.mast_street_names     n on s.street_id=n.street_id
                            left join eng.mast_address_subunits u on a.street_address_id=u.street_address_id
                            where $options)";
                $zend_db = Database::getConnection();
                $result = $zend_db->fetchAll($sql, $bindValues);

                foreach ($result as $row) {
                    if ($row['active'] == 'Y') {
                        $address = new Address($row['street_address_id']);
                        $subunit = !empty($row['subunit_id'])
                            ? new Subunit($row['subunit_id'])
                            : '';

                        $row['addressString'] = trim("$address $subunit");
                        return $row;
                    }
                }
            }
        }
	}
}
