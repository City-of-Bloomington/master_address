<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Locations;

use Application\Models\StatusRecord;

class Status extends StatusRecord
{
    protected $tablename = 'location_status';

	public function validate()
	{
        if (!$this->getLocation_id()) {
            throw new \Exception('missingRequiredFields');
        }
        parent::validate();
	}

	public function getLocation_id() { $id = parent::get('location_id'); return $id ? (int)$id : null; }
	public function getLocation()    { return parent::getForeignKeyObject(__namespace__.'\Location', 'location_id'); }

    public function setLocation_id (int $id) { parent::setForeignKeyField (__namespace__.'\Location', 'location_id', $id); }
    public function setLocation(Location $o) { parent::setForeignKeyObject(__namespace__.'\Location', 'location_id',  $o); }
}
