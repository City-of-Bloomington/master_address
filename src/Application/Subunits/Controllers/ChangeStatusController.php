<?php
/**
 * Change the status on a subunit
 *
 * @copyright 2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Subunits\Controllers;

use Application\Subunits\Views\ChangeStatusView;
use Domain\Subunits\UseCases\ChangeStatus\ChangeStatusRequest;

use Application\Controller;
use Application\View;

class ChangeStatusController extends Controller
{
    public function changeStatus(array $params): View
    {
        $subunit_id = !empty($_REQUEST['id']) ? (int)$_REQUEST['id'] : null;
        if ($subunit_id) {
            $change   = $this->di->get('Domain\Subunits\UseCases\ChangeStatus\ChangeStatus');
            $request  = new ChangeStatusRequest($subunit_id, $_SESSION['USER']->id, $_REQUEST);

            if (isset($_POST['status'])) {
                $response = $change($request);
                if (!$response->errors) {
                    header('Location: '.View::generateUrl('subunits.view', ['id'=>$subunit_id]));
                    exit();
                }
                $_SESSION['errorMessages'] = $response->errors;
            }

            $info = parent::subunitInfo($subunit_id);
            if (!$request->status) {
                $request->status = $info->subunit->status;
            }

            $contact = !empty($_REQUEST['contact_id']) ? parent::person((int)$_REQUEST['contact_id']) : null;

            return new ChangeStatusView($request, $info, $change::statuses(), $contact);
        }
        return new \Application\Views\NotFoundView();
    }
}
