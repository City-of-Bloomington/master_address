<?php
/**
 * Represents an instance of name being assigned to a street
 *
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Streets\Designations;

use Application\Streets\Change;
use Blossom\Classes\ActiveRecord;

class Designation extends ActiveRecord
{
    const TYPE_STREET     = 1;
    const TYPE_ALIAS      = 3;
    const TYPE_DATA       = 6;
    const TYPE_HISTORIC   = 2;
    const TYPE_MEMORIAL   = 5;
    const TYPE_STATE_ROAD = 4;

    public static $types = [
        1 => ['id'=>1, 'name'=>'STREET',     'description'=>'PRIMARY STREET NAME'],
        2 => ['id'=>2, 'name'=>'HISTORIC',   'description'=>'STREET NAME HISTORIC NAME'],
        3 => ['id'=>3, 'name'=>'ALIAS',      'description'=>'STREET NAME ALIAS'],
        4 => ['id'=>4, 'name'=>'STATE ROAD', 'description'=>'STREET NAME STATE ROAD NAME'],
        5 => ['id'=>5, 'name'=>'MEMORIAL',   'description'=>'MEMORIAL STREET NAME'],
        6 => ['id'=>6, 'name'=>'DATA',       'description'=>'STREET NAME NEEED FOR DATA ADMINSTRATION OR INTERGRATION']
    ];

    protected $tablename = 'street_designations';

    protected $street_name;
    protected $street;

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
			$this->setType_id(self::TYPE_ALIAS);
			$this->setStartDate(new \DateTime());
		}
	}

	public function validate()
	{
        if (!$this->getStreet_id() || !$this->getName_id() || !$this->getType_id()) {
            throw new \Exception('missingRequiredFields');
        }

        $id  = $this->getId();
        $sql = "select count(*) as count
                from {$this->tablename}       d
                join street_designation_types t on d.type_id=t.id
                where d.start_date<=now() and (d.end_date is null or now()<=d.end_date)
                  and d.street_id=?
                  and t.name=?
                  and d.id!=?";
        $rows = parent::doQuery($sql, [$this->getStreet_id(), self::TYPE_STREET ,$id]);
        if ($rows[0]['count']) {
            throw new \Exception('streetDesignations/duplicateCurrent');
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
	public function getType_id()     { return (int)parent::get('type_id'       ); }
	public function getStartDate()   { return      parent::get('start_date'    ); }
	public function getEndDate()     { return      parent::get('end_date'      ); }
    public function getStreet()      { return parent::getForeignKeyObject('Application\Streets\Street',     'street_id'     ); }
    public function getName()        { return parent::getForeignKeyObject('Application\Streets\Names\Name', 'street_name_id'); }
    public function getType()        { $id = $this->getType_id(); return $id ? self::$types[$id] : null; }

    public function setRank           (int $i=null) { parent::set('rank',    $i                                             ? $i : null); }
	public function setType_id        (int $i=null) { parent::set('type_id', ($i && in_array($i, array_keys(self::$types))) ? $i : null); }
    public function setStartDate(\DateTime $d=null) { parent::set('start_date', $d); }
	public function setEndDate  (\DateTime $d=null) { parent::set(  'end_date', $d); }
	public function setStreet_id  (int $id) { parent::setForeignKeyField ('Application\Streets\Street',     'street_id', $id); }
	public function setName_id    (int $id) { parent::setForeignKeyField ('Application\Streets\Names\Name', 'name_id',   $id); }
	public function setNameType_id(int $id) { parent::setForeignKeyField (__namespace__.'Type',             'type_id',   $id); }
	public function setStreet   (Street $o) { parent::setForeignKeyObject('Application\Streets\Street',     'street_id', $o ); }
	public function setName       (Name $o) { parent::setForeignKeyObject('Application\Streets\Names\Name', 'name_id',   $o ); }


	//----------------------------------------------------------------
	// Actions
	//----------------------------------------------------------------
	public function update(array $post)
	{
        $this->setName_id  ($post['name_id'  ]);
        $this->setType_id  ($post['type_id'  ]);
        $this->setStartDate($post['startDate']);
        $this->setEndDate  ($post['endDate'  ]);
        $this->setRank     ($post['rank'     ]);
        $this->validate();

        $change = new Change();
        $change->setAction('alias');
        $change->setStreet($this->getStreet());
        $change->setPerson    ($post['user'      ]);
        $change->setContact_id($post['contact_id']);
        $change->setNotes     ($post['notes'     ]);
        $change->validate();

        $this->save();
        $change->save();
	}

	//----------------------------------------------------------------
	// Custom Functions
	//----------------------------------------------------------------
	public function toArray():array
	{
        return $this->data;
	}
}
