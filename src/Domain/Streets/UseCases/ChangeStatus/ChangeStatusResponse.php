<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\UseCases\ChangeStatus;

class ChangeStatusResponse
{
    public $entry_id;    // The ID for the row in the log table
    public $street_id;
    public $errors = [];

    public function __construct(?int $entry_id=null, ?int $street_id=null, ?array $errors=null)
    {
        $this->entry_id  = $entry_id;
        $this->street_id = $street_id;
        $this->errors    = $errors;
    }
}