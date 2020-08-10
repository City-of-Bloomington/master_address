<?php
/**
 * Activate an address on a location
 *
 * There should only be one active address per location
 *
 * @copyright 2020 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Addresses\Controllers;

use Application\Controller;
use Application\View;
use Application\Views\NotFoundView;

use Application\Addresses\Views\ActivateView;
use Domain\Addresses\UseCases\Activate\Request as ActivateRequest;

class ActivateController extends Controller
{
    public function activate(array $params): View
    {
         $address_id = !empty($_REQUEST[ 'address_id']) ? (int)$_REQUEST[ 'address_id'] : null;
        $location_id = !empty($_REQUEST['location_id']) ? (int)$_REQUEST['location_id'] : null;

        if ($address_id && $location_id) {
            $request = new ActivateRequest($address_id, $location_id, $_SESSION['USER']->id, $_REQUEST);
            if (isset($_POST['address_id'])) {
                $activate = $this->di->get('Domain\Addresses\UseCases\Activate\Command');
                $response = $activate($request);
                if (!$response->errors) {
                    header('Location: '.View::generateUrl('addresses.view', ['id'=>$address_id]));
                    exit();
                }

                $_SESSION['errorMessages'] = $response->errors;
            }
            $info    = parent::addressInfo($address_id);
            $contact = !empty($_REQUEST['contact_id']) ? parent::person((int)$_REQUEST['contact_id']) : null;
            return new ActivateView($request, $info, $contact);
        }

        if ($address_id) {
            $_SESSION['errorMessages'][] = 'missingLocation';
            $url = View::generateUrl('addresses.view', ['id'=>$address_id]);
            header("Location: $url");
            exit();
        }
        return new NotFoundView();
    }
}
