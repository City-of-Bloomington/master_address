<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Models;

use Blossom\Classes\ActiveRecord;

abstract class StatusRecord extends ActiveRecord
{
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

	public function __construct($id=null)
	{
		if ($id) {
			if (is_array($id)) { $this->populate($id); }
			else {
                $sql  = "select * from {$this->tablename} where id=?";
				$rows = self::doQuery($sql, [$id]);
                if (count($rows)) { $this->populate($rows[0]); }
                else { throw new \Exception("{$this->tablename}/unknown"); }
			}
		}
		else {
			// This is where the code goes to generate a new, empty instance.
			// Set any default values for properties that need it here
			$this->setStartDate(new \DateTime());
		}
	}

	public function validate() { if (!$this->getStatus()) { throw new \Exception('missingRequiredFields'); } }
	public function save() { parent::save(); }

	//----------------------------------------------------------------
	// Generic Getters and Setters
	//----------------------------------------------------------------
	public function getId()        { return (int)parent::get('id'); }
	public function getStatus()    { return parent::get('status'    ); }
	public function getStartDate() { return parent::get('start_date'); }
    public function getEndDate()   { return parent::get('end_date'  ); }

    public function setStatus($s) { parent::set('status', $s); }
    public function setStartDate(\DateTime $d=null) { parent::set('start_date', $d); }
    public function setEndDate  (\DateTime $d=null) { parent::set(  'end_date', $d); }

	//----------------------------------------------------------------
	// Custom Functions
	//----------------------------------------------------------------
	public function __toString() { return $this->getStatus(); }
}
