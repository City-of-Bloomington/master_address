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
    protected $direction;
    protected $postDirection;
    protected $suffix_code;

    public function validate()
    {
        if (!$this->getName()) { throw new \Exception('missingRequiredFields'); }
    }

    public function save() { parent::save(); }
	//----------------------------------------------------------------
	// Generic Getters & Setters
	//----------------------------------------------------------------
	public function getId()    { return (int)parent::get('id'   ); }
	public function getName()  { return      parent::get('name' ); }
	public function getNotes() { return      parent::get('notes'); }
	public function getDirection_id()     { $id = (int)parent::get('direction_id'     ); return $id ? $id : null; }
    public function getPostDirection_id() { $id = (int)parent::get('post_direction_id'); return $id ? $id : null; }
    public function getSuffixCode_id()    { $id = (int)parent::get('suffix_code_id'   ); return $id ? $id : null; }
    public function getDirection()     { return parent::getForeignKeyObject('Application\Models\Direction', 'direction_id'     ); }
    public function getPostDirection() { return parent::getForeignKeyObject('Application\Models\Direction', 'post_direction_id'); }
    public function getSuffixCode()    { return parent::getForeignKeyObject(__namespace__.'\Type',          'suffix_code_id'   ); }

    public function setName ($s) { parent::set('name',  $s); }
    public function setNotes($s) { parent::set('notes', $s); }
    public function setDirection_id    (int $i=null) { parent::setForeignKeyField ('Application\Models\Direction', 'direction_id',      $i ? $i : null); }
    public function setPostDirection_id(int $i=null) { parent::setForeignKeyField ('Application\Models\Direction', 'post_direction_id', $i ? $i : null); }
    public function setSuffixCode_id   (int $i=null) { parent::setForeignKeyField (__namespace__.'\Type',          'suffix_code_id',    $i ? $i : null); }
	public function setDirection    (Direction $o)   { parent::setForeignKeyObject('Application\Models\Direction', 'direction_id',      $o); }
	public function setPostDirection(Direction $o)   { parent::setForeignKeyObject('Application\Models\Direction', 'post_direction_id', $o); }
	public function setSuffixCode        (Type $o)   { parent::setForeignKeyObject(__namespace__.'\Type',          'suffix_code_id',    $o); }

	public function handleUpdate(array $post)
	{
        $this->setName ($post['name' ]);
        $this->setNotes($post['notes']);
        $this->setDirection_id    ((int)$post['direction_id'     ]);
        $this->setPostDirection_id((int)$post['post_direction_id']);
        $this->setSuffixCode_id   ((int)$post['suffix_code_id'   ]);
	}

	//----------------------------------------------------------------
	// Custom Functions
	//----------------------------------------------------------------
	public function __toString()
	{
        $name = '';
        if ($this->getDirection_id()    ) { $name =     $this->getDirection()    ->getCode().' '; }
        $name.= $this->getName();
        if ($this->getSuffixCode_id()   ) { $name.= ' '.$this->getSuffixCode()   ->getCode(); }
        if ($this->getPostDirection_id()) { $name.= ' '.$this->getPostDirection()->getCode(); }
        return $name;
	}
}
