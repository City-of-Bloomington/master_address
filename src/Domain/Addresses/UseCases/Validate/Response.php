<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\Validate;

use Domain\Addresses\Entities\Address;

class Response
{
    public $address;
    public $errors = [];

    public function __construct(?Address $address=null, ?array $errors=null)
    {
        $this->address = $address;
        $this->errors  = $errors;
    }
}
