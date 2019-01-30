<?php
/**
 * @copyright 2017-2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Addresses\Views;

use Application\Block;
use Application\Template;
use Application\Paginator;

use Domain\Addresses\UseCases\Search\SearchResponse;
use Domain\Reports\Report;
use Domain\Reports\ReportResponse;

class SearchView extends Template
{
    public function __construct(SearchResponse     $response,
                                int                $itemsPerPage,
                                int                $currentPage,
                                ?Report            $report=null,
                                ?ReportResponse    $rres  =null)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);

        $this->vars['title'] = $this->_('addresses_search');
        if ($response->errors) {
            $_SESSION['errorMessages'] = $response->errors;
        }

        if ($format != 'html') {
            $this->blocks = [
                new Block('addresses/list.inc', ['addresses' => $response->addresses])
            ];
        }
        else {
            $this->blocks[] = new Block('addresses/findForm.inc', ['addresses' => $response->addresses]);
            if ($response->total > $itemsPerPage) {
                $this->blocks[] = new Block('pageNavigation.inc', [
                    'paginator' => new Paginator(
                        $response->total,
                        $itemsPerPage,
                        $currentPage
                )]);
            }

            if ($report) {
                $this->blocks[] = new Block('reports/output.inc', [
                    'title'          => $this->_('activity'),
                    'report'         => $report::metadata(),
                    'results'        => $rres->results,
                    'disableButtons' => true
                ]);
            }
        }
    }
}
