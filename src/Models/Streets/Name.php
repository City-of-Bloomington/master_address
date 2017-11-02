<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Models\Streets;

use Application\Models\Direction;
use Blossom\Classes\ActiveRecord;

class Name extends ActiveRecord
{
    protected $tablename = 'street_names';
    protected $suffix_code;

    public function validate()
    {
        if (!$this->getName()) { throw new \Exception('missingRequiredFields'); }
    }

    public function save() { parent::save(); }
	//----------------------------------------------------------------
	// Generic Getters & Setters
	//----------------------------------------------------------------
	public function getId()            { return (int)parent::get('id'            ); }
	public function getName()          { return      parent::get('name'          ); }
	public function getNotes()         { return      parent::get('notes'         ); }
    public function getDirection()     { return      parent::get('direction'     ); }
    public function getPostDirection() { return      parent::get('post_direction'); }
    public function getSuffixCode_id()    { $id = (int)parent::get('suffix_code_id'   ); return $id ? $id : null; }
    public function getSuffixCode()    { return parent::getForeignKeyObject(__namespace__.'\Type',          'suffix_code_id'   ); }

    public function setName         ($s) { parent::set('name',           $s); }
    public function setNotes        ($s) { parent::set('notes',          $s); }
    public function setDirection    ($s) { parent::set('direction',      $s); }
    public function setPostDirection($s) { parent::set('post_direction', $s); }
    public function setSuffixCode_id   (int $i=null) { parent::setForeignKeyField (__namespace__.'\Type',          'suffix_code_id',    $i ? $i : null); }
	public function setSuffixCode        (Type $o)   { parent::setForeignKeyObject(__namespace__.'\Type',          'suffix_code_id',    $o); }

	public function handleUpdate(array $post)
	{
        $this->setName         (     $post['name'          ]);
        $this->setNotes        (     $post['notes'         ]);
        $this->setDirection    (     $post['direction'     ]);
        $this->setPostDirection(     $post['post_direction']);
        $this->setSuffixCode_id((int)$post['suffix_code_id']);
	}

	//----------------------------------------------------------------
	// Custom Functions
	//----------------------------------------------------------------
	public function __toString()
	{
        $name = '';
        if ($this->getDirection()) { $name = $this->getDirection().' '; }
        $name.= $this->getName();
        if ($this->getSuffixCode_id()) { $name.= ' '.$this->getSuffixCode()->getCode(); }
        if ($this->getPostDirection()) { $name.= ' '.$this->getPostDirection(); }
        return $name;
	}
}
