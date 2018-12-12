<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Locations\UseCases\Find;

class FindResponse
{
    public $locations;
    public $errors;

    public function __construct(?array $locations=null, ?array $errors=null)
    {
        $this->locations = $locations;
        $this->errors    = $errors;
    }
}
