<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Streets;

use Application\Towns\Town;
use Application\Addresses\AddressesTable;
use Application\Streets\ChangeLog;
use Application\Streets\StreetNamesTable;

use Blossom\Classes\ActiveRecord;

class Street extends ActiveRecord
{
    protected $tablename = 'streets';
    protected $town;

    private $streetName;

    public function validate()
    {
        if (!$this->getStatus_id()) { throw new \Exception('missingRequiredFields'); }
    }

    public function save() { parent::save(); }

	//----------------------------------------------------------------
	// Generic Getters & Setters
	//----------------------------------------------------------------
	public function getId()        { return (int)parent::get('id'); }
	public function getTown_id()   { $id = (int)parent::get('town_id'  ); return $id ? $id : null; }
	public function getStatus()    { return parent::get('status'); }
	public function getNotes()     { return parent::get('notes' ); }
	public function getTown()      { return parent::getForeignKeyObject('Application\Towns\Town', 'town_id'); }

	public function setStatus($s) { parent::set('status', $s); }
	public function setNotes ($s) { parent::set('notes',  $s); }
	public function setTown_id  (int $i=null) { parent::setForeignKeyField ('Application\Towns\Town', 'town_id', $i ? $i : null); }
	public function setTown    (Town $o)      { parent::setForeignKeyObject('Application\Towns\Town', 'town_id', $o); }

	public function handleUpdate(array $post)
	{
        $this->setNotes    (     $post['notes'    ]);
        $this->setStatus   (     $post['status'   ]);
        $this->setTown_id  ((int)$post['town_id'  ]);
	}

	//----------------------------------------------------------------
	// Custom Functions
	//----------------------------------------------------------------
	public function getName():string
	{
        $sn = $this->getStreetName();
        if ($sn) {
            return $sn->getName()->__toString();
        }
        return '';
	}

	public function getStreetName()
	{
        if (!$this->streetName) {
            $type = new NameType('STREET');

            $table = new StreetNamesTable();
            $list  = $table->find(['street_id'=>$this->getId(), 'type_id'=>$type->getId()]);
            if (count($list)) {
                $this->streetName = $list[0];
            }
        }
        return $this->streetName;
	}

	/**
	 * @return array
	 */
	public function getStreetNames()
	{
        $table = new StreetNamesTable();
        $list  = $table->find(['street_id'=>$this->getId()]);
        return $list;
	}

	public function getAddresses()
	{
        $table = new AddressesTable();
        $list  = $table->find(['street_id'=>$this->getId()]);
        return $list;
	}

	public function getChangeLog()
	{
        $table = new ChangeLog();
        $list  = $table->find(['street_id'=>$this->getId()]);
        return $list;
	}
}
