<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Locations\Controllers;

use Application\Locations\Views\ListView;

use Application\Controller;
use Application\View;
use Application\Views\NotFoundView;

use Domain\Locations\UseCases\Find\FindRequest;

class ViewController extends Controller
{
    public function view(array $params): View
    {
        if (!empty($_GET['id'])) {
            $find = $this->di->get('Domain\Locations\UseCases\Find\Find');
            $req  = new FindRequest(['location_id' => (int)$_GET['id']]);
            $res  = $find($req);
            if ($res->locations) {
                return new ListView($res);
            }
            else {
                $_SESSION['errorMessages'] = $res->errors;
            }
        }
        return new NotFoundView();
    }
}
