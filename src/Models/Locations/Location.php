<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Models\Locations;

use Application\Models\Addresses\Address;
use Application\Models\Addresses\Subunit;
use Blossom\Classes\ActiveRecord;

class Location extends ActiveRecord
{
    protected $tablename  = 'locations';
    protected $primaryKey = 'location_id';

    protected $type;
    protected $address;
    protected $subunit;

	public function validate()
	{
        if (!$this->getType_id() || !$this->getAddress_id()) {
            throw new \Exception('missingRequiredFields');
        }
	}

	public function save() { parent::save(); }

	//----------------------------------------------------------------
	// Generic Getters and Setters
	//----------------------------------------------------------------
    public function getId()    { return  (int)parent::get('location_id'); }
    public function getType_id()     { $id =  parent::get('type_id'    ); return $id ? (int)$id : null; }
    public function getAddress_id()  { $id =  parent::get('address_id' ); return $id ? (int)$id : null; }
    public function getSubunit_id()  { $id =  parent::get('subunit_id' ); return $id ? (int)$id : null; }
    public function getMailable()    { return parent::get('mailable'   ) ? true : false; }
    public function getOccupiable()  { return parent::get('occupiable' ) ? true : false; }
    public function getActive()      { return parent::get('active'     ) ? true : false; }
    public function getTrashDay()    { return parent::get('trashDay'   ); }
    public function getRecycleWeek() { return parent::get('recycleWeek'); }
    public function getType()    { return parent::getForeignKeyObject(__namespace__.'\Type',                     'type_id'); }
    public function getAddress() { return parent::getForeignKeyObject('Application\Models\Addresses\Address', 'address_id'); }
    public function getSubunit() { return parent::getForeignKeyObject('Application\Models\Addresses\Subunit', 'subunit_id'); }

    public function setType_id   (int $id) { parent::setForeignKeyField (__namespace__.'\Type',                     'type_id', $id); }
    public function setAddress_id(int $id) { parent::setForeignKeyField ('Application\Models\Addresses\Address', 'address_id', $id); }
    public function setSubunit_id(int $id) { parent::setForeignKeyField ('Application\Models\Addresses\Subunit', 'subunit_id', $id); }
    public function setType      (Type $o) { parent::setForeignKeyObject(__namespace__.'\Type',                     'type_id',  $o); }
    public function setAddress(Address $o) { parent::setForeignKeyObject('Application\Models\Addresses\Address', 'address_id',  $o); }
    public function setSubunit(Subunit $o) { parent::setForeignKeyObject('Application\Models\Addresses\Subunit', 'subunit_id',  $o); }
    public function setMailable   ($b) { parent::set('mailable',    $b ? true : false); }
    public function setOccupiable ($b) { parent::set('occupiable',  $b ? true : false); }
    public function setActive     ($b) { parent::set('active',      $b ? true : false); }
    public function setTrashDay   ($s) { parent::set('trashDay',    $s); }
    public function setRecycleWeek($s) { parent::set('recycleWeek', $s); }

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
}
