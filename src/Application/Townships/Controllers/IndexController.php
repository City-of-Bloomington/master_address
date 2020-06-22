<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Townships\Controllers;

use Application\Townships\Views\ListView;
use Domain\Townships\UseCases\Search\SearchRequest;

use Application\Controller as BaseController;
use Application\View;

class IndexController extends BaseController
{
    public function index(array $params): View
    {
        $search = $this->di->get('Domain\Townships\UseCases\Search\Search');
        $res    = $search(new SearchRequest());

        return new ListView($res);
    }
}
