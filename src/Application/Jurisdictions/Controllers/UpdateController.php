<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Jurisdictions\Controllers;

use Application\Jurisdictions\Views\UpdateView;
use Domain\Jurisdictions\Entities\Jurisdiction;
use Domain\Jurisdictions\UseCases\Info\InfoRequest;
use Domain\Jurisdictions\UseCases\Update\UpdateRequest;

use Application\Controller;
use Application\View;

class UpdateController extends Controller
{
    public function update(array $params): View
    {
        if (isset($_POST['name'])) {
            $update   = $this->di->get('Domain\Jurisdictions\UseCases\Update\Update');
            $request  = new UpdateRequest($_POST);
            $response = $update($request);
            if (!count($response->errors)) {
                header('Location: '.View::generateUrl('jurisdictions.index'));
                exit();
            }
            else {
                $_SESSION['errorMessages'] = $response->errors;
            }
            $jurisdiction = new Jurisdiction((array)$request);
        }
        elseif (!empty($_REQUEST['id'])) {
            $info = $this->di->get('Domain\Jurisdictions\UseCases\Info\Info');
            $req  = new InfoRequest((int)$_REQUEST['id']);
            try {
                $res  = $info($req);
                $jurisdiction = $res->jurisdiction;
            }
            catch (\Exception $e) {
                $_SESSION['errorMessages'] = $res->errors;
                return new \Application\Views\NotFoundView();
            }
        }
        else { $jurisdiction = new Jurisdiction(); }

        return new UpdateView($jurisdiction, isset($response) ? $response : null);
    }
}
