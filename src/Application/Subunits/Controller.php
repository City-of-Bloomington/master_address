<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Subunits;

use Application\Controller as BaseController;
use Application\View;

use Domain\Subunits\UseCases\Add\AddRequest;
use Domain\Subunits\UseCases\ChangeStatus\ChangeStatusRequest;
use Domain\Subunits\UseCases\Correct\CorrectRequest;
use Domain\Subunits\UseCases\Verify\VerifyRequest;

class Controller extends BaseController
{
    public function view(array $params): View
    {
        if (!empty($_GET['id'])) {
            $info = parent::subunitInfo((int)$_GET['id']);
            if ($info->subunit) {
                return new Views\InfoView($info);
            }
            else {
                $_SESSION['errorMessages'] = $info->errors;
            }
        }
        return new \Application\Views\NotFoundView();
    }

    public function add(array $params): View
    {
        global $DEFAULTS;
        $request = new AddRequest($_SESSION['USER']->id, $_REQUEST);
        if (!$request->locationType_id) { $request->locationType_id = $DEFAULTS['locationType_id']; }

        if ($request->address_id) {
            $addressInfo = parent::addressInfo($request->address_id);

            if (isset($_POST['address_id'])) {
                $add      = $this->di->get('Domain\Subunits\UseCases\Add\Add');
                $response = $add($request);
                if (!$response->errors) {
                    header('Location: '.View::generateUrl('addresses.view', ['id'=>$request->address_id]));
                    exit();
                }
                else { $_SESSION['errorMessages'] = $response->errors; }
            }

            return new Views\AddView(
                $request,
                $addressInfo,
                $this->di->get('Domain\Subunits\Metadata'),
                $this->di->get('Domain\Locations\Metadata'),
                !empty($_GET['contact_id']) ? parent::person((int)$_GET['contact_id']) : null
            );
        }
        else {
            return new \Application\Views\NotFoundView();
        }
    }

    /**
     * Correct an error in the primary attributes of a subunit
     */
    public function correct(array $params): View
    {
        $subunit_id = !empty($_REQUEST['id']) ? (int)$_REQUEST['id'] : null;
        if ($subunit_id) {
            if (isset($_POST['id'])) {
                $request  = new CorrectRequest($subunit_id, $_SESSION['USER']->id, $_POST);
                $correct  = $this->di->get('Domain\Subunits\UseCases\Correct\Correct');
                $response = $correct($request);
                if (!count($response->errors)) {
                    header('Location: '.View::generateUrl('subunits.view', ['id'=>$subunit_id]));
                    exit();
                }
                else { $_SESSION['errorMessages'] = $response->errors; }
            }

            $info    = parent::subunitInfo($subunit_id);
            $contact = !empty($_REQUEST['contact_id']) ? parent::person((int)$_REQUEST['contact_id']) : null;
            if (!isset($request)) {
                $request = new CorrectRequest($subunit_id, $_SESSION['USER']->id, [
                    'type_id'    => $info->subunit->type_id,
                    'identifier' => $info->subunit->identifier,
                    'notes'      => $info->subunit->notes,
                    'contact_id' => $contact ? $contact->id : null
                ]);
            }
            return new Views\CorrectView(
                $request,
                $info,
                $this->di->get('Domain\Subunits\Metadata'),
                $contact
            );
        }
        return new \Application\Views\NotFoundView();
    }

    /**
     * Change the status on a subunit
     */
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

            return new Views\ChangeStatusView($request, $info, $change::statuses(), $contact);
        }
        return new \Application\Views\NotFoundView();
    }

    /**
     * Create a changeLog comment, declaring you have verified this subunit.
     */
    public function verify(array $params): View
    {
        $subunit_id = !empty($_REQUEST['id']) ? (int)$_REQUEST['id'] : null;
        if ($subunit_id) {
            $request = new VerifyRequest($subunit_id, $_SESSION['USER']->id, $_REQUEST);
            if (isset($_POST['id'])) {
                $verify = $this->di->get('Domain\Subunits\UseCases\Verify\Verify');
                $response = $verify($request);
                if (!$response->errors) {
                    header('Location: '.View::generateUrl('subunits.view', ['id'=>$subunit_id]));
                    exit();
                }
                $_SESSION['errorMessages'] = $response->errors;
            }

            return new Views\VerifyView(
                $request,
                parent::subunitInfo($subunit_id),
                !empty($_REQUEST['contact_id']) ? parent::person((int)$_REQUEST['contact_id']) : null
            );
        }
        return new \Application\Views\NotFoundView();
    }
}
