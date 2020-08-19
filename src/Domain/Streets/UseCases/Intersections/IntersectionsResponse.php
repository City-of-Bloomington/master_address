<?php
/**
 * @copyright 2019-2020 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Streets\UseCases\Intersections;

class IntersectionsResponse
{
    public $intersections;
    public $errors;

    public function __construct(?array $intersections=null, ?array $errors=null)
    {
        $this->intersections = $intersections;
        $this->errors        = $errors;
    }
}
