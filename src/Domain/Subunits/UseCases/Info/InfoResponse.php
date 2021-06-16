<?php
/**
 * @copyright 2018-2021 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Subunits\UseCases\Info;

use Domain\Locations\Entities\Location;

class InfoResponse
{
    public $subunit;
    public $address;
    public $locations = [];
    public $places    = [];
    public $changeLog = [];
    public $statusLog = [];
    public $errors    = [];

    public function activeCurrentLocation(): Location
    {
        foreach ($this->locations as $l) {
            if ($l->active) {
                return $l;
            }
        }
    }
}
