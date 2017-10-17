<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Models;

use Application\TableGateways\AddressesTable;
use Blossom\Classes\ActiveRecord;
use Blossom\Classes\Database;

class Plat extends ActiveRecord
{
    protected $tablename = 'plats';
    protected $township;

    // Maps this model's fieldnames to database column names
    // [field => column]
    public static $fieldmap = [
        'id'          => 'id',
        'name'        => 'name',
        'type'        => 'plat_type',
        'cabinet'     => 'cabinet',
        'envelope'    => 'envelope',
        'notes'       => 'notes',
        'startDate'   => 'start_date',
        'endDate'     => 'end_date',
        'township_id' => 'township_id'
    ];

    public static $types = ['A', 'C', 'S'];

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

	/**
	 * Throws an exception if anything's wrong
	 * @throws Exception $e
	 */
	public function validate()
	{
        if (!$this->getName() || !$this->getType() || !$this->getTownship_id()) {
			throw new \Exception('missingRequriedFields');
		}
	}

	public function save() { parent::save(); }

	//----------------------------------------------------------------
	// Generic Getters and Setters
	//----------------------------------------------------------------
	public function getId()          { return (int)parent::get('id'); }
	public function getName()        { return parent::get('name'       ); }
	public function getType()        { return parent::get('plat_type'  ); }
	public function getCabinet()     { return parent::get('cabinet'    ); }
	public function getEnvelope()    { return parent::get('envelope'   ); }
	public function getNotes()       { return parent::get('notes'      ); }
	public function getStartDate()   { return parent::get('start_date' ); }
	public function getEndDate()     { return parent::get('end_date'   ); }
	public function getTownship_id() { return (int)parent::get('township_id'); }
	public function getTownship()    { return parent::getForeignKeyObject(__namespace__.'\Township', 'township_id'); }

	public function setName     (string $s) { parent::set('name',      $s); }
	public function setType     (string $s) { parent::set('plat_type', $s); }
	public function setCabinet  (string $s) { parent::set('cabinet',   $s); }
	public function setEnvelope (string $s) { parent::set('envelope',  $s); }
	public function setNotes    (string $s) { parent::set('notes',     $s); }
	public function setStartDate(\DateTime $d=null) { parent::set('start_date', $d); }
	public function setEndDate  (\DateTime $d=null) { parent::set(  'end_date', $d); }
	public function setTownship_id(int $id)  { parent::setForeignKeyField (__namespace__.'\Township', 'township_id', $id); }
	public function setTownship(Township $o) { parent::setForeignKeyObject(__namespace__.'\Township', 'township_id', $o ); }

	public function handleUpdate(array $post)
	{
        $this->setName    ($post['name'    ]);
        $this->setType    ($post['type'    ]);
        $this->setCabinet ($post['cabinet' ]);
        $this->setEnvelope($post['envelope']);
        $this->setNotes   ($post['notes'   ]);
        $this->setTownship_id((int)$post['township_id']);
        $this->setStartDate(parent::parseDate($post['startDate'], DATE_FORMAT));
        $this->setEndDate  (parent::parseDate($post['endDate'  ], DATE_FORMAT));
	}

	//----------------------------------------------------------------
	// Custom Functions
	//----------------------------------------------------------------
	/**
	 * Returns the list of available cabinets
	 */
	public static function getCabinets() : array
	{
        $pdo   = Database::getConnection();
        $query = $pdo->prepare('select distinct cabinet from plats where cabinet is not null order by cabinet');
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_COLUMN, 0);
	}


	public function getAddresses()
	{
        if ($this->getId()) {
            $table = new AddressesTable();
            return $table->find(['plat_id'=>$this->getId()]);
        }
	}
}
