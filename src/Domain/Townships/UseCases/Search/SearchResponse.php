<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Townships\UseCases\Search;

class SearchResponse
{
    public $townships = [];
    public $errors    = [];

    public function __construct(array $townships, array $errors=null)
    {
        $this->townships = $townships;
        $this->errors    = $errors;
    }
}
