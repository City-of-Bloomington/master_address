<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Towns\Controllers;

use Application\Towns\Views\UpdateView;
use Domain\Towns\UseCases\Info\InfoRequest;
use Domain\Towns\UseCases\Update\UpdateRequest;

use Application\Controller as BaseController;
use Application\View;

class UpdateController extends BaseController
{
    public function update(array $params)
    {
        if (isset($_POST['name'])) {
            $update   = $this->di->get('Domain\Towns\UseCases\Update\Update');
            $request  = new UpdateRequest($_POST);
            $response = $update($request);
            if (!$response->errors) {
                header('Location: '.View::generateUrl('towns.index'));
                exit();
            }
            $town = new Town((array)$request);
        }
        elseif (!empty($_REQUEST['id'])) {
            $info = $this->di->get('Domain\Towns\UseCases\Info\Info');
            $req  = new InfoRequest((int)$_REQUEST['id']);
            try {
                $res  = $info($req);
                $town = $res->town;
            }
            catch (\Exception $e) {
                $_SESSION['errorMessages'] = $res->errors;
                return new \Application\Views\NotFoundView();
            }
        }
        else { $town = new Town(); }

        return new UpdateView($town, isset($response) ? $response : null);
    }
}
