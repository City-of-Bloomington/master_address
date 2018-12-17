<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Locations\Entities;

class Sanitation
{
    public $location_id;
    public $trash_day;
    public $recycle_week;

    public function __construct(?array $data=null)
    {
        if ($data) {
            if (isset($data['location_id' ])) { $this->location_id  = (int)$data['location_id' ]; }
            if (isset($data['trash_day'   ])) { $this->trash_day    =      $data['trash_day'   ]; }
            if (isset($data['recycle_week'])) { $this->recycle_week =      $data['recycle_week']; }
        }
    }
}
