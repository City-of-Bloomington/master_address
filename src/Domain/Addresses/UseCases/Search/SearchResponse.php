<?php
/**
 * @copyright 2018-2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\Search;

class SearchResponse
{
    public $addresses;
    public $errors;
    public $total;

    public function __construct(?array $addresses=null, ?int $total=0, ?array $errors=null)
    {
        $this->addresses = $addresses;
        $this->errors    = $errors;
        $this->total     = $total;
    }
}
