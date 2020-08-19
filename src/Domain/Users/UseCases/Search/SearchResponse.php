<?php
/**
 * @copyright 2018-2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Users\UseCases\Search;

class SearchResponse
{
    public $users;
    public $errors;
    public $total;

    public function __construct(?array $users=null, ?int $total=null, ?array $errors=null)
    {
        $this->users  = $users;
        $this->total  = $total;
        $this->errors = $errors;
    }
}
