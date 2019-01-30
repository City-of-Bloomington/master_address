<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Reports;

abstract class Report
{
    protected $pdo;

    /**
     * List all the custom reports installed in SITE_HOME
     */
    public static function list(): array
    {
        $reports = [];
        foreach (glob(SITE_HOME.'/src/Reports/*.php') as $f) {
            preg_match('/(^.*)\.([^\.]+)$/', basename($f), $matches);
            $reports[] = $matches[1];
        }
        return $reports;
    }

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    abstract public function metadata(): array;
    abstract public function execute(array $request, ?int $itemsPerPage=null, ?int $currentPage=null): ReportResponse;
}
