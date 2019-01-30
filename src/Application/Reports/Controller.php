<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Reports;

use Application\Controller as BaseController;
use Application\View;

class Controller extends BaseController
{
    const ITEMS_PER_PAGE = 10;

    /**
     * List the available reports
     */
    public function index(array $params): View
    {
        $reports = \Domain\Reports\Report::list();
        return new Views\ListView($reports);
    }

    /**
     * @param string name
     */
    public function report(array $params): View
    {
        try { $report = $this->di->get("Site\Reports\\$params[name]"); }
        catch (\Exception $e) { return new \Application\Views\NotFoundView(); }

		$page     = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
        $metadata = $report->metadata();
        $request  = [];

        foreach ($metadata['params'] as $k=>$p) {
            switch ($p['type']) {
                case 'date':
                    $request[$k] = !empty($_REQUEST[$k])
                                 ? new \DateTime($_REQUEST[$k])
                                 : (!empty($p['default']) ? new \DateTime($p['default']) : null);
                break;

                default:
                    $request[$k] = !empty($_REQUEST[$k])
                                 ? $_REQUEST[$k]
                                 : (!empty($p['default']) ? $p['default'] : null);
            }
        }


        $response = $report->execute($request, self::ITEMS_PER_PAGE, $page);

        return new Views\ReportView($report, self::ITEMS_PER_PAGE, $page, $request, $response);
    }
}
