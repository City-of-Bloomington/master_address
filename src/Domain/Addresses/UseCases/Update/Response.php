<?php
/**
 * @copyright 2019-2020 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\Update;

class Response
{
    public $entry_id;    // ID from the log entry row
    public $address_id;
    public $errors;

    public function __construct(?int $entry_id=null, ?int $address_id=null, ?array $errors=null)
    {
        $this->entry_id   = $entry_id;
        $this->address_id = $address_id;
        $this->errors     = $errors;
    }
}
