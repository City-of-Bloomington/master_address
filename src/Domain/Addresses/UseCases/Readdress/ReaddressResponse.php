<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\Readdress;

class ReaddressResponse
{
    public $entry_id;    // The ID for the row in the log table
    public $address_id;
    public $errors = [];

    public function __construct(?int $entry_id=null, ?int $address_id=null, ?array $errors=null)
    {
        $this->entry_id    = $entry_id;
        $this->address_id  = $address_id;
        $this->errors      = $errors;
    }
}
