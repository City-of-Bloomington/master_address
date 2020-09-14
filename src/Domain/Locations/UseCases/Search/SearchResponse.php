<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Locations\UseCases\Search;

class SearchResponse
{
    public $locations;
    public $errors;
    public $total;

    public function __construct(?array $locations=null, ?int $total=0, ?array $errors=null)
    {
        $this->locations = $locations;
        $this->errors    = $errors;
        $this->total     = $total;
    }
}
