<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Jurisdictions\Controllers;

use Application\Jurisdictions\Views\ListView;
use Domain\Jurisdictions\UseCases\Search\SearchRequest;

use Application\Controller;
use Application\View;

class IndexController extends Controller
{
    public function index(array $params): View
    {
        $search = $this->di->get('Domain\Jurisdictions\UseCases\Search\Search');
        $res    = $search(new SearchRequest());

        return new ListView($res);
    }
}
