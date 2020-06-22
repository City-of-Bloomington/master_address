<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Townships\Controllers;

use Application\Townships\Views\UpdateView;
use Domain\Townships\Entities\Township;
use Domain\Townships\UseCases\Info\InfoRequest;
use Domain\Townships\UseCases\Update\UpdateRequest;

use Application\Controller as BaseController;
use Application\View;

class UpdateController extends BaseController
{
    public function update(array $params): View
    {
        if (isset($_POST['name'])) {
            $update   = $this->di->get('Domain\Townships\UseCases\Update\Update');
            $request  = new UpdateRequest($_POST);
            $response = $update($request);
            if (!count($response->errors)) {
                header('Location: '.View::generateUrl('townships.index'));
                exit();
            }
            else {
                $_SESSION['errorMessages'] = $response->errors;
            }
            $township = new Township((array)$request);
        }
        elseif (!empty($_REQUEST['id'])) {
            $info = $this->di->get('Domain\Townships\UseCases\Info\Info');
            $req  = new InfoRequest((int)$_REQUEST['id']);
            try {
                $res      = $info($req);
                $township = $res->township;
            }
            catch (\Exception $e) {
                $_SESSION['errorMessages'] = $res->errors;
                return new \Application\Views\NotFoundView();
            }
        }
        else { $township = new Township(); }

        return new UpdateView($township, isset($response) ? $response : null);
    }
}
