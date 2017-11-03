<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Models\Addresses;

use Application\Models\StatusRecord;

class Status extends StatusRecord
{
    protected $tablename = 'address_status';

	public function validate()
	{
        if (!$this->getAddress_id()) {
            throw new \Exception('missingRequiredFields');
        }
        parent::validate();
	}

	public function getAddress_id() { $id = parent::get('address_id'); return $id ? (int)$id : null; }
	public function getAddress()   { return parent::getForeignKeyObject(__namespace__.'\Address', 'address_id'); }

    public function setAddress_id(int $id) { parent::setForeignKeyField (__namespace__.'\Address', 'address_id', $id); }
    public function setAddress(Address $o) { parent::setForeignKeyObject(__namespace__.'\Address', 'address_id',  $o); }
}
