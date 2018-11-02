<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Streets\UseCases\ChangeName;

class ChangeNameResponse
{
    public $entry_id;       // The ID of the change log message
    public $designation_id; // The ID of the new designation
    public $errors = [];

    public function __construct(?int $entry_id, ?int $designation_id=null, ?array $errors=null)
    {
        $this->entry_id       = $entry_id;
        $this->designation_id = $designation_id;
        $this->errors         = $errors;
    }
}
