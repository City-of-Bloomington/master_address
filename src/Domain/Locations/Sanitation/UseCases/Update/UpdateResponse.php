<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Locations\Sanitation\UseCases\Update;

class UpdateResponse
{
    public $location_id;
    public $errors = [];

    public function __construct(?int $location_id=null, ?array $errors=null)
    {
        $this->location_id = $location_id;
        $this->errors      = $errors;
    }
}
