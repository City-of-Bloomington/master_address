<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Reports;

class ReportResponse
{
    public $results = [];
    public $total   = 0;
    public $errors  = [];

    public function __construct(?array $results=null, ?int $total=0, ?array $errors=null)
    {
        $this->results = $results;
        $this->total   = $total;
        $this->errors  = $errors;
    }
}
