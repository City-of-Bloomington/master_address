<?php
/**
 * @copyright 2017-2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Addresses\Views;

use Application\Block;
use Application\Template;

use Domain\Addresses\Metadata;
use Domain\Addresses\UseCases\Search\SearchRequest;
use Domain\Addresses\UseCases\Search\SearchResponse;
use Domain\Reports\Report;
use Domain\Reports\ReportResponse;

class SearchView extends Template
{
    public function __construct(SearchRequest      $request,
                                SearchResponse     $response,
                                int                $itemsPerPage,
                                int                $currentPage,
                                Metadata           $address,
                                ?Report            $report = null,
                                ?ReportResponse    $rres   = null)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);

        $this->vars['title'] = $this->_('addresses_search');
        if ($response->errors) {
            $_SESSION['errorMessages'] = $response->errors;
        }

        $vars = [
            'searching'    => !$request->isEmpty(),
            'addresses'    => $response->addresses,
            'total'        => $response->total,
            'itemsPerPage' => $itemsPerPage,
            'currentPage'  => $currentPage,
            'directions'   => $address::$directions,
            'cities'       => $address->cities(),
            'streetTypes'  => $address->streetTypes()
        ];
        if ($format != 'html') {
            $this->blocks = [
                new Block('addresses/list.inc', $vars)
            ];
        }
        else {
            foreach ($request as $k=>$v) { $vars[$k] = parent::escape($v); }

            $this->blocks[] = new Block('addresses/findForm.inc', $vars);
        }
    }
}
