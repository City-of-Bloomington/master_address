<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Places\Actions\Search;

class Response
{
    public $places;
    public $errors;
    public $total;

    public function __construct(?array $places=null, ?int $total=0, ?array $errors=null)
    {
        $this->places = $places;
        $this->errors = $errors;
        $this->total  = $total;
    }
}
