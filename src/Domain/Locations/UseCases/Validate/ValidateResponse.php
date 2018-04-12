<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Locations\UseCases\Validate;

use Domain\Locations\Entities\Location;

class ValidateResponse
{
    public $location;
    public $errors = [];

    public function __construct(Location $location, ?array $errors=[])
    {
        $this->location = $location;
        $this->errors   = $errors;
    }
}
