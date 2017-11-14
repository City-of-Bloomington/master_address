<?php
/**
 * A class to encapsulate the information logged whenever someone makes a change
 * to something in the database.  All of the log tables will have these same fields
 *
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Models;

use Application\People\Person;
use Blossom\Classes\ActiveRecord;

class ChangeLogEntry extends ActiveRecord
{
	protected $person;
	protected $contact;

	public static $actions = [  'add'=>'added','assign'=>'assigned','activate'=>'activated',
                                'create'=>'created','propose'=>'proposed','correct'=>'corrected',
                                'alias'=>'added alias','change'=>'changed street name',
                                'update'=>'updated','move'=>'moved to location',
                                'readdress'=>'readdressed','reassign'=>'reassigned',
                                'unretire'=>'unretired','retire'=>'retired',
                                'verify'=>'verified'];

    /**
     * Convert date strings to DateTime objects before loading
     */
    private function populate(array $row)
    {
        $row['action_date'] = !empty($row['action_date'])
            ? parent::parseDate(     $row['action_date'], ActiveRecord::DB_DATE_FORMAT)
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

				$rows = parent::doQuery($sql, [$id]);
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
			$this->setActionDate(new \DateTime());
		}
	}

	public function validate()
	{
        if (!$this->getActionDate()) { $this->setActionDate(new \DateTime()); }

        if (!$this->getPerson_id() || !$this->getAction()) {
            throw new \Exception('missingRequiredFields');
        }

        if (!in_array($this->getAction(), array_keys(self::$actions))) {
            throw new \Exception('logEntry/unknownAction');
        }
	}

	public function save() { parent::save(); }

	//----------------------------------------------------------------
	// Generic Getters and Setters
	//----------------------------------------------------------------
    public function getId()         { return (int)parent::get('id'         ); }
    public function getAction()     { return      parent::get('action'     ); }
    public function getNotes()      { return      parent::get('notes'      ); }
    public function getActionDate() { return      parent::get('action_date'); }
    public function getPerson_id()  {  $id = (int)parent::get('person_id'  ); return $id ? $id : null; }
    public function getContact_id() {  $id = (int)parent::get('contact_id' ); return $id ? $id : null; }
    public function getPerson()  { return parent::getForeignKeyObject('\Application\People\Person', 'person_id' ); }
    public function getContact() { return parent::getForeignKeyObject('\Application\People\Person', 'contact_id'); }

    public function setAction($s) { parent::set('action', $s); }
    public function setNotes ($s) { parent::set('notes',  $s); }
    public function setActionDate(\DateTime $d=null) { parent::set('action_date', $d); }
    public function setPerson_id      (int $id=null) { parent::setForeignKeyField ('\Application\People\Person', 'person_id',  $id); }
    public function setContact_id     (int $id=null) { parent::setForeignKeyField ('\Application\People\Person', 'contact_id', $id); }
    public function setPerson       (Person $p=null) { parent::setForeignKeyObject('\Application\People\Person', 'person_id',  $p ); }
    public function setContact      (Person $p=null) { parent::setForeignKeyObject('\Application\People\Person', 'contact_id', $p ); }
}
