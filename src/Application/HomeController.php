<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application;

use Application\View;

class HomeController extends Controller
{
    public function index(array $params): View
    {
        if (View::isAllowed('reports', 'report')) {
            $reports = new \Application\Reports\Controller();
            return $reports->report(['name'=>'AddressActivity']);
        }
        return new Template('default');
    }
}
