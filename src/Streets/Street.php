<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Streets;

use Application\Addresses\AddressesTable;
use Application\People\Person;
use Application\Towns\Town;

use Blossom\Classes\ActiveRecord;

class Street extends ActiveRecord
{
    protected $tablename = 'streets';
    protected $town;

    private $designation;

    public static $actions  = ['verify', 'correct', 'changeStatus'];
    public static $statuses = ['current', 'retired', 'proposed', 'corrected'];

    public function validate()
    {
        if (!$this->getStatus()) { throw new \Exception('missingRequiredFields'); }
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

	//----------------------------------------------------------------
	// Actions
	//----------------------------------------------------------------
	public function verify(Messages\VerifyRequest $req)
	{
        $change = new Change();
        $change->setAction('verify');
        $change->setPerson($req->user);
        $change->setStreet($this);
        $change->setNotes ($req->notes);
        $change->save();
	}

	public function correct(Messages\CorrectRequest $req)
	{
        $this->setNotes    (     $req->streetInfo['notes'  ]);
        $this->setTown_id  ((int)$req->streetInfo['town_id']);
        $this->validate();

        $change = new Change();
        $change->setAction('correct');
        $change->setPerson($req->user);
        $change->setStreet($this);
        $change->setContact_id((int)$req->changeLog['contact_id']);
        $change->setNotes     (     $req->changeLog['notes'     ]);
        $change->validate();

        $this->save();
        $change->save();
	}

	public function changeStatus(Messages\StatusChangeRequest $req)
	{
        $current = $this->getStatus();
        if ($current != $req->status) {
            $action = $req->status == 'current'
                    ? $current == 'retired' ? 'unretired' : 'activated'
                    : $req->status;

            $this->setStatus($req->status);
            $this->validate();

            $change = new Change();
            $change->setAction($action);
            $change->setPerson($req->user);
            $change->setStreet($this);
            $change->setNotes ($req->notes);
            $change->validate();

            $this->save();
            $change->save();
        }
	}

	//----------------------------------------------------------------
	// Custom Functions
	//----------------------------------------------------------------
	public function getName():string
	{
        $sn = $this->getDesignation();
        if ($sn) {
            return $sn->getName()->__toString();
        }
        return '';
	}

	public function getDesignation()
	{
        if (!$this->designation) {
            $table = new Designations\DesignationsTable();
            $list  = $table->find([
                'street_id' => $this->getId(),
                'type_id'   => Designations\Designation::TYPE_STREET
            ]);
            if (count($list)) {
                $this->designation = $list[0];
            }
        }
        return $this->designation;
	}

	/**
	 * @return array
	 */
	public function getDesignations()
	{
        $table = new Designations\DesignationsTable();
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
