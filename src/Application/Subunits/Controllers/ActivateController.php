<?php
/**
 * Activate a subunit on a location
 *
 * There should only be one active subunit per location
 *
 * @copyright 2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Subunits\Controllers;

use Application\Subunits\Views\ActivateView;
use Domain\Subunits\UseCases\Activate\Request as ActivateRequest;

use Application\Controller;
use Application\View;

class ActivateController extends Controller
{
    public function activate(array $params): View
    {
         $subunit_id = !empty($_REQUEST[ 'subunit_id']) ? (int)$_REQUEST[ 'subunit_id'] : null;
        $location_id = !empty($_REQUEST['location_id']) ? (int)$_REQUEST['location_id'] : null;

        if ($subunit_id && $location_id) {
            $request = new ActivateRequest($subunit_id, $location_id, $_SESSION['USER']->id, $_REQUEST);
            if (isset($_POST['subunit_id'])) {
                $activate = $this->di->get('Domain\Subunits\UseCases\Activate\Command');
                $response = $activate($request);
                if (!$response->errors) {
                    header('Location: '.View::generateUrl('subunits.view', ['id'=>$subunit_id]));
                    exit();
                }

                $_SESSION['errorMessages'] = $response->errors;
            }
            $info    = parent::subunitInfo($subunit_id);
            $contact = !empty($_REQUEST['contact_id']) ? parent::person((int)$_REQUEST['contact_id']) : null;
            return new ActivateView($request, $info, $contact);
        }

        if ($subunit_id) {
            $_SESSION['errorMessages'][] = 'missingLocation';
            $url = View::generateUrl('subunits.view', ['id'=>$subunit_id]);
            header("Location: $url");
            exit();
        }
        return new \Application\Views\NotFoundView();
    }
}
