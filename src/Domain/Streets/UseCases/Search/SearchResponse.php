<?php
/**
 * @copyright 2018-2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Streets\UseCases\Search;

class SearchResponse
{
    public $streets;
    public $errors;
    public $total;

    public function __construct(?array $streets=null, ?int $total=0, ?array $errors=null)
    {
        $this->streets = $streets;
        $this->errors  = $errors;
        $this->total   = $total;
    }
}
