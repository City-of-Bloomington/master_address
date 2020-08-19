<?php
/**
 * @copyright 2018-2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\People\UseCases\Search;

class SearchResponse
{
    public $people;
    public $errors;
    public $total;

    public function __construct(?array $people=null, ?int $total=0, ?array $errors=null)
    {
        $this->people = $people;
        $this->total  = $total;
        $this->errors = $errors;
    }
}
