<?php
/**
 * Represents an instance of name being assigned to a street
 *
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Models\Streets;

use Blossom\Classes\ActiveRecord;

class StreetName extends ActiveRecord
{
    protected $tablename = 'street_street_names';

    protected $street_name;
    protected $street;
    protected $type;

    /**
     * Converts date strings into DateTime objects
     */
    private function populate(array $row)
    {
        $row['start_date'] = !empty($row['start_date'])
            ? parent::parseDate(    $row['start_date'], ActiveRecord::DB_DATE_FORMAT)
            : null;
        $row[  'end_date'] = !empty($row[  'end_date'])
            ? parent::parseDate(    $row[  'end_date'], ActiveRecord::DB_DATE_FORMAT)
            : null;

        $this->data = $row;
    }

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
                $this->populate($id);
			}
			else {
                $sql = "select * from {$this->tablename} where id=?";

				$rows = self::doQuery($sql, [$id]);
                if (count($rows)) {
                    $this->populate($rows[0]);
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
        if (!$this->getStreet_id() || !$this->getName_id() || !$this->getType_id()) {
            throw new \Exception('missingRequiredFields');
        }
	}

	public function save() { parent::save(); }

	//----------------------------------------------------------------
	// Generic Getters and Setters
	//----------------------------------------------------------------
	public function getId()          { return (int)parent::get('id'            ); }
	public function getRank()        { $id =  (int)parent::get('rank'); return $id ? $id : null; }
	public function getStreet_id()   { return (int)parent::get('street_id'     ); }
	public function getName_id()     { return (int)parent::get('street_name_id'); }
	public function getNameType_id() { return (int)parent::get('type_id'       ); }
	public function getStartDate()   { return      parent::get('start_date'    ); }
	public function getEndDate()     { return      parent::get('end_date'      ); }
    public function getStreet()      { return parent::getForeignKeyObject(__namespace__.'\Street',   'street_id'     ); }
    public function getName()        { return parent::getForeignKeyObject(__namespace__.'\Name',     'street_name_id'); }
    public function getNameType()    { return parent::getForeignKeyObject(__namespace__.'\NameType', 'type_id'       ); }

    public function setRank           (int $i=null) { parent::set('rank', $i ? $i : null); }
    public function setStartDate(\DateTime $d=null) { parent::set('start_date', $d); }
	public function setEndDate  (\DateTime $d=null) { parent::set(  'end_date', $d); }
	public function setStreet_id   (int $id) { parent::setForeignKeyField (__namespace__.'\Street',   'street_id', $id); }
	public function setName_id     (int $id) { parent::setForeignKeyField (__namespace__.'\Name',     'name_id',   $id); }
	public function setNameType_id (int $id) { parent::setForeignKeyField (__namespace__.'\NameType', 'type_id',   $id); }
	public function setStreet    (Street $o) { parent::setForeignKeyObject(__namespace__.'\Street',   'street_id', $o ); }
	public function setName        (Name $o) { parent::setForeignKeyObject(__namespace__.'\Name',     'name_id',   $o ); }
	public function setNameType(NameType $o) { parent::setForeignKeyObject(__namespace__.'\NameType', 'type_id',   $o ); }
}
