<?php
/**
 * Represents a single entry in the Street Change Log
 *
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Models\Streets;

use Application\Models\ChangeLogEntry;

class Change extends ChangeLogEntry
{
    protected $tablename = 'street_change_log';
    protected $street;

    public function getStreet_id() { return (int)parent::get('street_id'); }
    public function getStreet()    { return parent::getForeignKeyObject(__namespace__.'\Street', 'street_id'); }

    public function setStreet_id(int $id) { parent::setForeignKeyField (__namespace__.'\Street', 'street_id', $id); }
    public function setStreet (Street $o) { parent::setForeignKeyObject(__namespace__.'\Street', 'street_id',  $o); }

    public function handleUpdate(array $post)
    {
        $this->setStreet_id($post['street_id']);
        parent::handleUpdate($post);
    }
}
