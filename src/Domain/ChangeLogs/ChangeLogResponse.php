<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\ChangeLogs;

class ChangeLogResponse
{
    public $entry_id;
    public $errors = [];
    
    public function __construct(?int $entry_id=null, ?array $errors=null)
    {
        $this->entry_id = $entry_id;
        $this->errors   = $errors;
    }
}
