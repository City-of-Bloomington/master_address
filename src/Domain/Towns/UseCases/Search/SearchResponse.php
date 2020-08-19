<?php
/**
 * @copyright 2018-2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Towns\UseCases\Search;

class SearchResponse
{
    public $towns;
    public $errors;

    public function __construct(?array $towns=null, ?array $errors=null)
    {
        $this->towns  = $towns;
        $this->errors = $errors;
    }
}
