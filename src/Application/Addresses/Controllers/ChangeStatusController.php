<?php
/**
 * Change the status on an address
 *
 * @copyright 2020 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Addresses\Controllers;

use Application\Controller;
use Application\View;
use Application\Views\NotFoundView;

use Application\Addresses\Views\ChangeStatusView;
use Domain\Addresses\UseCases\ChangeStatus\ChangeStatusRequest;

class ChangeStatusController extends Controller
{
    public function changeStatus(array $params): View
    {
        $address_id = !empty($_REQUEST['id']) ? (int)$_REQUEST['id'] : null;
        if ($address_id) {
            $change  = $this->di->get('Domain\Addresses\UseCases\ChangeStatus\ChangeStatus');
            $request = new ChangeStatusRequest($address_id, $_SESSION['USER']->id, $_REQUEST);

            if (isset($_POST['status'])) {
                $response = $change($request);
                if (!$response->errors) {
                    header('Location: '.View::generateUrl('addresses.view', ['id'=>$address_id]));
                    exit();
                }
                else { $_SESSION['errorMessages'] = $response->errors; }
            }

            $info     = parent::addressInfo($address_id);
            $statuses = $change::statuses();
            $contact  = !empty($_REQUEST['contact_id']) ? parent::person((int)$_REQUEST['contact_id']) : null;
            if (!$request->status) {
                $request->status = $info->address->status;
            }

            return new ChangeStatusView($request, $info, $statuses, $contact);
        }
        return new NotFoundView();
    }
}
