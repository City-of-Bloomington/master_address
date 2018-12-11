<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\Names\UseCases\Search;

class SearchResponse
{
    public $names  = [];
    public $errors = [];
    public $total  = 0;

    public function __construct(?array $names=null, ?int $total=0, ?array $errors=null)
    {
        $this->names  = $names;
        $this->errors = $errors;
        $this->total  = $total;
    }
}
