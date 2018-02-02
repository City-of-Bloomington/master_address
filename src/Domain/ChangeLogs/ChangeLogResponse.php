<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\ChangeLogs;

class ChangeLogResponse
{
    public $changeLog = [];
    public $errors    = [];

    public function __construct(?array $changeLog=null, ?array $errors=null)
    {
        $this->changeLog = $changeLog;
        $this->errors    = $errors;
    }
}
