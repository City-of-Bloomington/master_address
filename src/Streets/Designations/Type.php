<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Streets\Designations;
use Blossom\Classes\ActiveRecord;

class Type extends ActiveRecord
{
    protected $tablename = 'street_designation_types';

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
                $sql = ActiveRecord::isId($id)
                    ? "select * from {$this->tablename} where id=?"
                    : "select * from {$this->tablename} where name=?";

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
        if (!$this->getName() || !$this->getDescription()) {
            throw new \Exception('missingRequiredFields');
        }
	}

	public function save() { parent::save(); }

	//----------------------------------------------------------------
	// Generic Getters & Setters
	//----------------------------------------------------------------
	public function getId()          { return (int)parent::get('id'         ); }
	public function getName()        { return      parent::get('name'       ); }
	public function getDescription() { return      parent::get('description'); }

	public function setName       (string $s) { parent::set('name',        $s); }
	public function setDescription(string $s) { parent::set('description', $s); }

	public function handleUpdate(array $post)
	{
        $this->setName       ($post['name'       ]);
        $this->setDescription($post['description']);
	}

	//----------------------------------------------------------------
	// Custom Functions
	//----------------------------------------------------------------
	public function __toString() { return $this->getName(); }
}
