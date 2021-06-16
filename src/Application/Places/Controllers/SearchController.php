<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Places\Controllers;

use Application\Places\Views\SearchView;
use Application\Controller;
use Application\View;

use Domain\Places\Actions\Search\Request;
use Domain\Places\Actions\Search\Response;

class SearchController extends Controller
{
    public function search(array $params): View
    {
		$page     = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
        $search   = $this->di->get('Domain\Places\Actions\Search\Command');
        $request  = new Request($_GET, null, parent::ITEMS_PER_PAGE, $page);
        $response = $search($request);

        return new SearchView($request, $response, parent::ITEMS_PER_PAGE, $page);
    }
}
