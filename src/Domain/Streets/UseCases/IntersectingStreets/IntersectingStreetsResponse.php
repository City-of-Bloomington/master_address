<?php
/**
 * @copyright 2018-2020 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Streets\UseCases\IntersectingStreets;

class IntersectingStreetsResponse
{
    public $streets;
    public $errors;

    public function __construct(?array $streets=null, ?array $errors=null)
    {
        $this->streets = $streets;
        $this->errors  = $errors;
    }
}
