<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Addresses\Controllers;

use Application\Addresses\Views\SearchView;
use Application\Controller;
use Application\View;

use Domain\Addresses\UseCases\Search\SearchRequest;
use Domain\Addresses\UseCases\Search\SearchResponse;

class SearchController extends Controller
{
    public function search(array $params): View
    {
		$page     = !empty($_GET['page']) ? (int)$_GET['page'] : 1;

        $search   = $this->di->get('Domain\Addresses\UseCases\Search\Search');
        $parser   = $this->di->get('Domain\Addresses\UseCases\Parse\Parse');
        $metadata = $this->di->get('Domain\Addresses\Metadata');

        $query    =  !empty($_GET['address'])
                  ? $parser($_GET['address'])->toSearchQuery()
                  : $_GET;

        $request  = new SearchRequest($query, null, parent::ITEMS_PER_PAGE, $page);
        $response = !$request->isEmpty() ? $search($request) : new SearchResponse();

        if (View::isAllowed('reports', 'report')) {
            $report = $this->di->get('Site\Reports\AddressActivity\Report');
            $rres = $report->execute([
                'startDate' => new \DateTime('-30 day'),
                  'endDate' => new \DateTime()
            ], parent::ITEMS_PER_PAGE, 1);

            return new SearchView($request, $response, parent::ITEMS_PER_PAGE, $page, $metadata, $report, $rres);
        }
        return new SearchView($request, $response, parent::ITEMS_PER_PAGE, $page, $metadata);
    }
}
