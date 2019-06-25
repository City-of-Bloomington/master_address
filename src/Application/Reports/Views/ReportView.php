<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Reports\Views;

use Application\Block;
use Application\Template;
use Application\Paginator;

use Domain\Reports\Report;
use Domain\Reports\ReportResponse;

class ReportView extends Template
{
    public function __construct(Report          $report,
                                int             $itemsPerPage,
                                int             $currentPage,
                                array           $request)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);

        $metadata = $report->metadata();

        if ($format == 'html') {
            $response = $report->execute($request, $itemsPerPage, $currentPage);
            $this->vars['title'] = parent::escape($metadata['title']);

            $this->blocks = [
                new Block('reports/form.inc', [
                    'request' => $request,
                    'results' => $response->results,
                    'report'  => $metadata
                ])
            ];
            if ($response->total > $itemsPerPage) {
                $this->blocks[] = new Block('pageNavigation.inc', [
                    'paginator' => new Paginator(
                        $response->total,
                        $itemsPerPage,
                        $currentPage
                )]);
            }
        }
        else {
            $response = $report->execute($request);
            $this->vars['title'] = $metadata['name'];
            if ($response) {
                $this->blocks = [
                    new Block('reports/output.inc', ['results' => $response->results])
                ];
            }
        }
    }
}
