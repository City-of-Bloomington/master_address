<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\Validate;

use Domain\Addresses\Entities\Address;

class ValidateResponse
{
    public $address;
    public $errors = [];

    public function __construct(Address $address, ?array $errors=null)
    {
        $this->address = $address;
        if ($errors) { $this->errors = $errors; }
    }
}
