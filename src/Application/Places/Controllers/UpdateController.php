<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Places\Controllers;

use Application\Controller;
use Application\View;
use Application\Places\Views\UpdateView;

use Domain\Places\Actions\Update\Request;

class UpdateController extends Controller
{
    public function update(array $params): View
    {
        $place_id = !empty($_REQUEST['id']) ? (int)$_REQUEST['id'] : null;

        if (isset($_POST['id'])) {
            $update  = $this->di->get('Domain\Places\Actions\Update\Command');
            $req     = new Request($_POST);
            $res     = $update($req);

            if (!$res->errors) {
                header('Location: '.View::generateUrl('places.view', ['id'=>$place_id]));
                exit();
            }
        }
        elseif ($place_id) {
            $info = $this->di->get('Domain\Places\Actions\Info\Command');
            $ir   = $info($place_id);
            if ($ir->errors) {
                $_SESSION['errorMessages'] = $ir->errors;
                return new \Application\Views\NotFoundView();
            }
            $req = new Request((array)$ir->place);
        }
        else {
            $req = new Request();
        }

        return new UpdateView($req,
                              isset($res) ? $res : null,
                              $this->di->get('Domain\Places\Metadata'));
    }
}
