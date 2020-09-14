<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Locations\Controllers;

use Application\Controller;
use Application\View;
use Application\Locations\Views\SearchView;
use Domain\Locations\UseCases\Search\SearchRequest;
use Domain\Locations\UseCases\Search\SearchResponse;

class SearchController extends Controller
{
    public function search(array $params): View
    {
        $page     = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
        $search   = $this->di->get('Domain\Locations\UseCases\Search\Search');
        $metadata = $this->di->get('Domain\Addresses\Metadata');

        if (!empty($_GET['location'])) {
            $parse = $this->di->get('Domain\Addresses\UseCases\Parse\Parse');
            $res   = $parse($_GET['location']);
            $_GET  = array_merge($_GET, (array)$res);
        }
        $request  = new SearchRequest($_GET, null, parent::ITEMS_PER_PAGE, $page);
        $response = !$request->isEmpty() ? $search($request) : new SearchResponse();

        return new SearchView($request, $response, parent::ITEMS_PER_PAGE, $page);
    }
}
