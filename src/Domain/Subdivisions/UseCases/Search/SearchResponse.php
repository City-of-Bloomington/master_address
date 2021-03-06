<?php
/**
 * @copyright 2018-2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Subdivisions\UseCases\Search;

class SearchResponse
{
    public $subdivisions;
    public $errors;
    public $total;
    public $options;

    public function __construct(?array $subdivisions=null, ?array $options=null, ?int $total=0, ?array $errors=null)
    {
        $this->subdivisions = $subdivisions;
        $this->options      = $options;
        $this->total        = $total;
        $this->errors       = $errors;
    }
}
