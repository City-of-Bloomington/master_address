<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Locations;

use Blossom\Classes\ActiveRecord;

class Purpose extends ActiveRecord
{
    protected $tablename = 'location_purposes';

    // Maps this model's fieldnames to database column names
    // [field => column]
    public static $fieldmap = [
        'id'          => 'id',
        'name'        => 'name',
        'type'        => 'purpose_type',
    ];

    /**
     * Valid values for type
     *
     * These are sorted most commonly used first.
     */
    public static $types = [
        'HISTORIC DISTRICT',
        'NEIGHBORHOOD ASSOCIATION',
        'ECONOMIC DEVELOPMENT AREA',
        'RESIDENTIAL PARKING ZONE',
        'CITY COUNCIL DISTRICT',
        'REDEVELOPMENT ZONE',
        'OTHER'
    ];

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
	 * @param int|string|array $id (ID, name, code)
	 */
	public function __construct($id=null)
	{
		if ($id) {
			if (is_array($id)) { $this->data = $id; }
			else {
				if (ActiveRecord::isId($id)) {
					$sql  = "select * from {$this->tablename} where id=?";
                    $rows = parent::doQuery($sql, [$id]);
				}
				else {
					$sql  = "select * from {$this->tablename} where name=? or code=?";
                    $rows = parent::doQuery($sql, [$id, $id]);
				}

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
		if (!$this->getName() || !$this->getCode() || !$this->getType()) {
			throw new Exception('missingRequiredFields');
		}
	}

	public function save() { parent::save(); }

	//----------------------------------------------------------------
	// Generic Getters and Setters
	//----------------------------------------------------------------
	public function getId  () { return parent::get('id'          ); }
	public function getName() { return parent::get('name'        ); }
    public function getType() { return parent::get('purpose_type'); }

	public function setName($s) { parent::set('name',         $s); }
    public function setType($s) { parent::set('purpose_type', $s); }


    public function handleUpdate(array $post)
    {
        $this->setName($post['name']);
        $this->setType($post['type']);
    }
}
