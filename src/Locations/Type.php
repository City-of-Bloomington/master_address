<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Locations;

use Blossom\Classes\ActiveRecord;

class Type extends ActiveRecord
{
    protected $tablename = 'location_types';

    // Maps this model's fieldnames to database column names
    // [field => column]
    public static $fieldmap = [
        'id'          => 'id',
        'name'        => 'name',
        'code'        => 'code',
        'description' => 'description'
    ];

    // The character limit for Codes
    public $codeSize = 8;

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
		if (!$this->getName() || !$this->getCode()) {
			throw new Exception('missingRequiredFields');
		}
	}

	public function save() { parent::save(); }

	//----------------------------------------------------------------
	// Generic Getters and Setters
	//----------------------------------------------------------------
	public function getId         () { return parent::get('id'         ); }
	public function getName       () { return parent::get('name'       ); }
	public function getCode       () { return parent::get('code'       ); }
    public function getDescription() { return parent::get('description'); }

	public function setName       ($s) { parent::set('name',        $s); }
	public function setCode       ($s) { parent::set('code',        $s); }
    public function setDescription($s) { parent::set('description', $s); }

    public function handleUpdate(array $post)
    {
        $this->setName($post['name']);
        $this->setCode($post['code']);
        $this->setDescription($post['description']);
    }

	//----------------------------------------------------------------
	// Custom Functions
	//----------------------------------------------------------------
	public function __toString() { return $this->getName(); }
}
