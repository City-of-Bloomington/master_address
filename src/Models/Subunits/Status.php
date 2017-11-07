<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Models\Subunits;

use Application\Models\StatusRecord;

class Status extends StatusRecord
{
    protected $tablename = 'subunit_status';

	public function validate()
	{
        if (!$this->getSubunit_id()) {
            throw new \Exception('missingRequiredFields');
        }
        parent::validate();
	}

	public function getSubunit_id() { $id = parent::get('subunit_id'); return $id ? (int)$id : null; }
	public function getSubunit()   { return parent::getForeignKeyObject(__namespace__.'\Subunit', 'subunit_id'); }

    public function setSubunit_id(int $id) { parent::setForeignKeyField (__namespace__.'\Subunit', 'subunit_id', $id); }
    public function setSubunit(Subunit $o) { parent::setForeignKeyObject(__namespace__.'\Subunit', 'subunit_id',  $o); }
}
