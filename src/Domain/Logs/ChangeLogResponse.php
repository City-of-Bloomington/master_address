<?php
/**
 * @copyright 2018-2020 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Logs;

class ChangeLogResponse
{
    public $entries;
    public $total;
    public $errors;

    public function __construct(?array $entries=null, ?int $total=0, ?array $errors=null)
    {
        $this->entries = $entries;
        $this->total   = $total;
        $this->errors  = $errors;
    }
}
