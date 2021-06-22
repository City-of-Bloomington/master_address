<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Places\Controllers;

use Application\Controller;
use Application\View;
use Application\Places\Views\AddView;

use Domain\Places\Actions\Add\Request;

class AddController extends Controller
{
    public function add(array $params): View
    {
        if (isset($_POST['name'])) {
            $add = $this->di->get('Domain\Places\Actions\Add\Command');
            $req = new Request($_POST);
            $res = $add($req);

            if (!$res->errors) {
                header('Location: '.View::generateUrl('places.view', ['id'=>$res->id]));
                exit();
            }
        }
        else {
            $req = new Request();
        }

        return new AddView($req,
                           isset($res) ? $res : null,
                           $this->di->get('Domain\Places\Metadata'));
    }
}
