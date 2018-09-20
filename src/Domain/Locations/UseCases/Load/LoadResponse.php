<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Locations\UseCases\Load;

use Domain\Locations\Entities\Location;

class LoadResponse
{
    public $location;
    public $errors = [];

    public function __construct(?Location $location=null, ?array $errors=null)
    {
        $this->location = $location;
        $this->errors   = $errors;
    }
}
