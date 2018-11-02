<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Streets\Designations\UseCases\Update;

class UpdateResponse
{
    public $entry_id;        // ID of the change log message
    public $designation_id;
    public $errors = [];

    public function __construct(?int $entry_id=null, ?int $designation_id=null, ?array $errors=null)
    {
        $this->entry_id       = $entry_id;
        $this->designation_id = $designation_id;
        $this->errors         = $errors;
    }
}
