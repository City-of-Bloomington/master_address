<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Models\Addresses;

use Application\Models\Streets\Street;
use Application\Models\Jurisdiction;
use Application\Models\Township;
use Application\Models\Subdivision;
use Application\Models\Plat;

use Application\TableGateways\Addresses\ChangeLog;
use Application\TableGateways\Addresses\Statuses;
use Application\TableGateways\Locations\Locations;
use Application\TableGateways\Subunits\Subunits;

use Blossom\Classes\ActiveRecord;

class Address extends ActiveRecord
{
    protected $tablename = 'addresses';

    protected $street;
    protected $jurisdiction;
    protected $township;
    protected $subdivision;
    protected $plat;

    // Maps database fields to variable names
    //
    // We do not want to select the postgis geometry field.  This means
    // for our normal constructor we must explicitly list out the fields
    // in the select statement.
    //
    // Also, the database fieldnames do not make the best variable names.
    // In our source code, we will be using the variable names defined here
    // $fields [ database_name => variable_name ]
    public static $fields = [
        'id'                   => 'id',
        'street_number_prefix' => 'streetNumberPrefix',
        'street_number'        => 'streetNumber',
        'street_number_suffix' => 'streetNumberSuffix',
        'adddress2'            => 'address2',
        'address_type'         => 'type',
        'street_id'            => 'street_id',
        'jurisdiction_id'      => 'jurisdiction_id',
        'township_id'          => 'township_id',
        'subdivision_id'       => 'subdivision_id',
        'plat_id'              => 'plat_id',
        'section'              => 'section',
        'quarter_section'      => 'quarterSection',
        'plat_lot_number'      => 'platLotNumber',
        'city'                 => 'city',
        'state'                => 'state',
        'zip'                  => 'zip',
        'zipplus4'             => 'zipplus4',
        'state_plane_x'        => 'x',
        'state_plane_y'        => 'y',
        'latitude'             => 'latitude',
        'longitude'            => 'longitude',
        'usng'                 => 'USNG',
        'notes'                => 'notes'
    ];

    public static $trash_days    = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
    public static $recycle_weeks = ['A', 'B'];
    public static $actions       = ['correct','update','readdress','unretire','reassign','retire','verify'];

	/**
	 * Populates the object with data
	 *
	 * Passing in an associative array of data will populate this object without
	 * hitting the database.
	 *
	 * Passing in a scalar will load the data from the database.
	 * This will load all fields in the table as properties of this class.
	 * You may want to replace this with, or add your own extra, custom loading
	 *
	 * @param int|array $id
	 */
	public function __construct($id=null)
	{
		if ($id) {
			if (is_array($id)) {
                $this->data = $id;
			}
			else {
                $fields = implode(', ', array_keys(self::$fields));
                $sql = "select $fields from {$this->tablename} where id=?";

				$rows = self::doQuery($sql, [$id]);
                if (count($rows)) {
                    $this->data = $rows[0];
                }
                else {
                    throw new \Exception("{$this->tablename}/unknown");
                }
			}
		}
		else {
			// This is where the code goes to generate a new, empty instance.
			// Set any default values for properties that need it here
		}
	}

	public function validate()
	{
		// Check for required fields here.  Throw an exception if anything is missing.
		if ( !$this->getStreet_id() || !$this->getStreetNumber() || !$this->getZip()
            || !$this->getSection() || !$this->getType()
            || !$this->getJurisdiction_id() || !$this->getTownship_id()) {
			throw new \Exception('missingRequiredFields');
		}

		if (!in_array($this->getTrashDay(),    self::$trash_days   )) { throw new \Exception('addresses/invalidTrashDay'   ); }
		if (!in_array($this->getRecycleWeek(), self::$recycle_weeks)) { throw new \Exception('addresses/invalidRecycleWeek'); }

		// Make sure this is not a duplicate address
		$pdo = Database::getConnection();
		$sql = "select count(*) from addresses
				where street_id=?
				  and street_number_prefix=? and street_number=? and street_number_suffix=?";
        $query = $pdo->prepare($sql)->execute([
			$this->getStreet_id(),
			$this->getStreetNumberPrefix(), $this->getStreetNumber(), $this->getStreetNumberSuffix()
        ]);
        $count = $query->fetchColumn();
		if ((!$this->getId() && $count) || $count>1) {
			throw new \Exception('addresses/duplicateAddress');
		}
	}

	public function save() { parent::save(); }

	//----------------------------------------------------------------
	// Generic Getters and Setters
	//----------------------------------------------------------------
    public function getId()                 { return (int)parent::get('id'                  ); }
    public function getStreetNumberPrefix() { return      parent::get('street_number_prefix'); }
    public function getStreetNumber()       { return (int)parent::get('street_number'       ); }
    public function getStreetNumberSuffix() { return      parent::get('street_number_suffix'); }
    public function getAddress2()           { return      parent::get('adddress2'           ); }
    public function getType()               { return      parent::get('address_type'        ); }
    public function getStreet_id()          { return (int)parent::get('street_id'           ); }
    public function getJurisdiction_id()    { return (int)parent::get('jurisdiction_id'     ); }
    public function getTownship_id()        { return (int)parent::get('township_id'         ); }
    public function getSubdivision_id()     { return (int)parent::get('subdivision_id'      ); }
    public function getPlat_id()            { return (int)parent::get('plat_id'             ); }
    public function getSection()            { return      parent::get('section'             ); }
    public function getQuarterSection()     { return      parent::get('quarter_section'     ); }
    public function getPlatLotNumber()      { return      parent::get('plat_lot_number'     ); }
    public function getCity()               { return      parent::get('city'                ); }
    public function getState()              { return      parent::get('state'               ); }
    public function getZip()                { return      parent::get('zip'                 ); }
    public function getZipplus4()           { return      parent::get('zipplus4'            ); }
    public function getX()                  { return (int)parent::get('state_plane_x'       ); }
    public function getY()                  { return (int)parent::get('state_plane_y'       ); }
    public function getLatitude()           { return      parent::get('latitude'            ); }
    public function getLongitude()          { return      parent::get('longitude'           ); }
    public function getUSNG()               { return      parent::get('usng'                ); }
    public function getNotes()              { return      parent::get('notes'               ); }
    public function getStreet()       { return parent::getForeignKeyObject('Application\Models\Streets\Street', 'street_id'      ); }
    public function getJurisdiction() { return parent::getForeignKeyObject('Application\Models\Jurisdiction',   'jurisdiction_id'); }
    public function getTownship()     { return parent::getForeignKeyObject('Application\Models\Township',       'township_id'    ); }
    public function getSubdivision()  { return parent::getForeignKeyObject('Application\Models\Subdivision',    'subdivision_id' ); }
    public function getPlat()         { return parent::getForeignKeyObject('Application\Models\Plat',           'plat_id'        ); }


    public function setStreetNumberPrefix($s) { parent::set('street_number_prefix', $s); }
    public function setStreetNumber  (int $s) { parent::set('street_number',        $s); }
    public function setStreetNumberSuffix($s) { parent::set('street_number_suffix', $s); }
    public function setAddress2          ($s) { parent::set('adddress2',            $s); }
    public function setType              ($s) { parent::set('address_type',         $s); }
    public function setStreet_id           (int $id) { parent::setForeignKeyField ('Application\Models\Streets\Street', 'street_id',       $id); }
    public function setJurisdiction_id     (int $id) { parent::setForeignKeyField ('Application\Models\Jurisdiction',   'jurisdiction_id', $id); }
    public function setTownship_id         (int $id) { parent::setForeignKeyField ('Application\Models\Township',       'township_id',     $id); }
    public function setSubdivision_id      (int $id) { parent::setForeignKeyField ('Application\Models\Subdivision',    'subdivision_id',  $id); }
    public function setPlat_id             (int $id) { parent::setForeignKeyField ('Application\Models\Plat',           'plat_id',         $id); }
    public function setStreet      (Street       $o) { parent::setForeignKeyObject('Application\Models\Streets\Street', 'street_id',       $o ); }
    public function setJurisdiction(Jurisdiction $o) { parent::setForeignKeyObject('Application\Models\Jurisdiction',   'jurisdiction_id', $o ); }
    public function setTownship    (Township     $o) { parent::setForeignKeyObject('Application\Models\Township',       'township_id',     $o ); }
    public function setSubdivision (Subdivision  $o) { parent::setForeignKeyObject('Application\Models\Subdivision',    'subdivision_id',  $o ); }
    public function setPlat        (Plat         $o) { parent::setForeignKeyObject('Application\Models\Plat',           'plat_id',         $o ); }
    public function setSection       ($s) { parent::set('section',         $s); }
    public function setQuarterSection($s) { parent::set('quarter_section', $s); }
    public function setPlatLotNumber ($s) { parent::set('plat_lot_number', $s); }
    public function setCity          ($s) { parent::set('city',            $s); }
    public function setState         ($s) { parent::set('state',           $s); }
    public function setZip       (int $s) { parent::set('zip',             $s); }
    public function setZipplus4      ($s) { parent::set('zipplus4',        $s); }
    public function setX         (int $s) { parent::set('state_plane_x',   $s); }
    public function setY         (int $s) { parent::set('state_plane_y',   $s); }
    public function setLatitude      ($s) { parent::set('latitude',        $s); }
    public function setLongitude     ($s) { parent::set('longitude',       $s); }
    public function setUSNG          ($s) { parent::set('usng',            $s); }
    public function setNotes         ($s) { parent::set('notes',           $s); }

	//----------------------------------------------------------------
	// Custom Functions
	//----------------------------------------------------------------
	public function getStatus(\DateTime $date=null)
	{
        if (!$date) { $date = new \DateTime(); }
        $d   = $date->format(parent::DB_DATE_FORMAT);
        $sql = "select * from address_status
                where address_id=?
                  and start_date <= ?
                  and (end_date is null or end_date >= ?)";
        $result = parent::doQuery($sql, [$this->getId(), $d, $d]);
        if (count($result)) {
            return new Status($result[0]);
        }
	}

	public function getChangeLog()
	{
        $table = new ChangeLog();
        $list  = $table->find(['address_id'=>$this->getId()]);
        return $list;
	}

	public function toArray() : array
	{
        $output = [];
        foreach (self::$fields as $f) {
            $get = 'get'.ucfirst($f);
            $output[$f] = $this->$get();
        }
        return $output;
	}

	public function getLocations()
	{
        $table = new Locations();
        return $table->find(['address_id'=>$this->getId(), 'subunit_id'=>null]);
	}

	public function getSubunits()
	{
        $table = new Subunits();
        return $table->find(['address_id'=>$this->getId()]);
	}
}
