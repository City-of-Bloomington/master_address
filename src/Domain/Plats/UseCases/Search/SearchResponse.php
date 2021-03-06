<?php
/**
 * @copyright 2018-2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Plats\UseCases\Search;

class SearchResponse
{
    public $plats;
    public $errors;
    public $total;
    public $options;

    public function __construct(?array $plats=null, ?array $options=null, ?int $total=0, ?array $errors=null)
    {
        $this->plats   = $plats;
        $this->options = $options;
        $this->total   = $total;
        $this->errors  = $errors;
    }
}
