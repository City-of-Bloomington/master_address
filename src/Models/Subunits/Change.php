<?php
/**
 * Represents a single entry in the Street Change Log
 *
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Models\Subunits;

use Application\Models\ChangeLogEntry;

class Change extends ChangeLogEntry
{
    protected $tablename = 'subunit_change_log';
    protected $subunit;

    public function getSubunit_id() { return (int)parent::get('subunit_id'); }
    public function getSubunit()    { return parent::getForeignKeyObject(__namespace__.'\Subunit', 'subunit_id'); }

    public function setSubunit_id(int $id) { parent::setForeignKeyField (__namespace__.'\Subunit', 'subunit_id', $id); }
    public function setSubunit(Subunit $o) { parent::setForeignKeyObject(__namespace__.'\Subunit', 'subunit_id',  $o); }

    public function handleUpdate(array $post)
    {
        $this->setSubunit_id($post['subunit_id']);
        parent::handleUpdate($post);
    }
}
