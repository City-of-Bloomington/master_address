<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\UseCases\Validate;

use Domain\Streets\Entities\Street;

class ValidateResponse
{
    public $street;
    public $errors = [];

    public function __construct(Street $street, array $errors)
    {
        $this->street = $street;
        $this->errors = $errors;
    }
}
