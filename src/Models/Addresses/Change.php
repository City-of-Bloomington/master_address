<?php
/**
 * Represents a single entry in the Street Change Log
 *
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Models\Addresses;

use Application\Models\ChangeLogEntry;

class Change extends ChangeLogEntry
{
    protected $tablename = 'address_change_log';
    protected $address;

    public function getAddress_id() { return (int)parent::get('address_id'); }
    public function getAddress()    { return parent::getForeignKeyObject(__namespace__.'\Address', 'address_id'); }

    public function setAddress_id(int $id) { parent::setForeignKeyField (__namespace__.'\Address', 'address_id', $id); }
    public function setAddress(Address $o) { parent::setForeignKeyObject(__namespace__.'\Address', 'address_id',  $o); }

    public function handleUpdate(array $post)
    {
        $this->setAddress_id($post['address_id']);
        parent::handleUpdate($post);
    }
}
