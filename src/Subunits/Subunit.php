<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Subunits;

use Application\Models\Addresses\Address;
use Application\TableGateways\Locations\Locations;
use Blossom\Classes\ActiveRecord;

class Subunit extends ActiveRecord
{
    protected $tablename = 'subunits';

    protected $type;
    protected $address;

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
        'id'            => 'id',
        'address_id'    => 'address_id',
        'type_id'       => 'type_id',
        'identifier'    => 'identifier',
        'notes'         => 'notes',
        'state_plane_x' => 'x',
        'state_plane_y' => 'y',
        'latitude'      => 'latitude',
        'longitude'     => 'longitude',
        'usng'          => 'USNG'
    ];

    public static $actions = ['correct', 'retire', 'unretire', 'verify'];

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
			if (is_array($id)) { $this->data = $id; }
			else {
                $fields = implode(', ', array_keys(self::$fields));
                $sql = "select $fields from {$this->tablename} where id=?";

				$rows = parent::doQuery($sql, [$id]);
                if (count($rows)) { $this->data = $rows[0]; }
                else { throw new \Exception("{$this->tablename}/unknown"); }
			}
		}
		else {
			// This is where the code goes to generate a new, empty instance.
			// Set any default values for properties that need it here
		}
	}

    public function validate()
    {
        if (!$this->getAddress_id()) { throw new \Exception('missingRequiredFields'); }
    }

    public function save() { parent::save(); }

	//----------------------------------------------------------------
	// Generic Getters and Setters
	//----------------------------------------------------------------
	public function getId()         { return (int)parent::get('id'); }
    public function getAddress_id() { $id =  parent::get('address_id' ); return $id ? (int)$id : null; }
    public function getType_id()    { $id =  parent::get('type_id'    ); return $id ? (int)$id : null; }
	public function getIdentifier() { return parent::get('identifier'); }
	public function getNotes()      { return parent::get('notes'); }
    public function getX()          { return (int)parent::get('state_plane_x'); }
    public function getY()          { return (int)parent::get('state_plane_y'); }
    public function getLatitude()   { return      parent::get('latitude'     ); }
    public function getLongitude()  { return      parent::get('longitude'    ); }
    public function getUSNG()       { return      parent::get('usng'         ); }
    public function getType()       { return parent::getForeignKeyObject(__namespace__.'\Type',                     'type_id'); }
    public function getAddress()    { return parent::getForeignKeyObject('Application\Models\Addresses\Address', 'address_id'); }

    public function setType_id   (int $id) { parent::setForeignKeyField (__namespace__.'\Type',                     'type_id', $id); }
    public function setAddress_id(int $id) { parent::setForeignKeyField ('Application\Models\Addresses\Address', 'address_id', $id); }
    public function setType      (Type $o) { parent::setForeignKeyObject(__namespace__.'\Type',                     'type_id',  $o); }
    public function setAddress(Address $o) { parent::setForeignKeyObject('Application\Models\Addresses\Address', 'address_id',  $o); }
    public function setIdentifier     ($s) { parent::set('identifier',    $s); }
    public function setNotes          ($s) { parent::set('notes',         $s); }
    public function setX         (int  $s) { parent::set('state_plane_x', $s); }
    public function setY         (int  $s) { parent::set('state_plane_y', $s); }
    public function setLatitude       ($s) { parent::set('latitude',      $s); }
    public function setLongitude      ($s) { parent::set('longitude',     $s); }
    public function setUSNG           ($s) { parent::set('usng',          $s); }

	//----------------------------------------------------------------
	// Custom Functions
	//----------------------------------------------------------------
	public function getStatus(\DateTime $date=null)
	{
        if (!$date) { $date = new \DateTime(); }
        $d   = $date->format(parent::DB_DATE_FORMAT);
        $sql = "select * from subunit_status
                where subunit_id=?
                  and start_date <= ?
                  and (end_date is null or end_date >= ?)";
        $result = parent::doQuery($sql, [$this->getId(), $d, $d]);
        if (count($result)) {
            return new Status($result[0]);
        }
	}

	public function getLocations()
	{
        $table = new Locations();
        return $table->find(['subunit_id'=>$this->getId()]);
	}

	public function getChangeLog()
	{
        $table = new ChangeLog();
        $list  = $table->find(['subunit_id'=>$this->getId()]);
        return $list;
	}
}
