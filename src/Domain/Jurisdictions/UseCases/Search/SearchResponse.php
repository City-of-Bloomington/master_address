<?php
/**
 * @copyright 2018-2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Jurisdictions\UseCases\Search;

class SearchResponse
{
    public $jurisdictions;
    public $errors;

    public function __construct(?array $jurisdictions=null, ?array $errors=null)
    {
        $this->jurisdictions  = $jurisdictions;
        $this->errors         = $errors;
    }
}
