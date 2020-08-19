<?php
/**
 * @copyright 2018-2020 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\Renumber;

class RenumberResponse
{
    public $errors;

    public function __construct(?array $errors=null)
    {
        $this->errors = $errors;
    }
}
