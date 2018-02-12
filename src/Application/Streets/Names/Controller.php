<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);

namespace Application\Streets\Names;

use Application\Controller as BaseController;
use Application\Streets\Controller as StreetController;
use Domain\Streets\Names\UseCases\Search\SearchRequest;
use Domain\Streets\Names\UseCases\Search\SearchResponse;

class Controller extends BaseController
{
    const ITEMS_PER_PAGE = 20;

    public function index()
    {
		$page   =  !empty($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = $this->di->get('Domain\Streets\Names\UseCases\Search\Search');
        $parser = $this->di->get('Domain\Addresses\Parser');

        $query  = !empty($_GET['street'])
                ? StreetController::extractStreetFields($parser($_GET['street']))
                : null;
        $res    = $query
                ? $search(new SearchRequest($query, null, self::ITEMS_PER_PAGE, $page))
                : new SearchResponse();

        return new Views\SearchView($res, self::ITEMS_PER_PAGE, $page);
    }
}
