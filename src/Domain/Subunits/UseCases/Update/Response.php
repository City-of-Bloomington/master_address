<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Subunits\UseCases\Update;

class Response
{
    public $entry_id;    // ID from the log entry row
    public $subunit_id;
    public $errors = [];

    public function __construct(?int $entry_id=null, ?int $subunit_id=null, ?array $errors=null)
    {
        $this->entry_id   = $entry_id;
        $this->subunit_id = $subunit_id;
        $this->errors     = $errors;
    }
}
