<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Subunits;

use Application\Controller as BaseController;
use Application\View;

use Domain\Subunits\UseCases\Add\AddRequest;
use Domain\Subunits\UseCases\Correct\CorrectRequest;

class Controller extends BaseController
{
    public function view(array $params)
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

    public function add(array $params)
    {
        if (!empty($_REQUEST['address_id'])) {
            $addressInfo = parent::addressInfo((int)$_REQUEST['address_id']);
        }

        if ($addressInfo) {
            if (isset($_POST['address_id'])) {
                $add      = $this->di->get('Domain\Subunits\UseCases\Add\Add');
                $request  = new AddRequest($_SESSION['USER']->id, $_POST);
                $response = $add($request);
                if (!count($response->errors)) {
                    header('Location: '.View::generateUrl('address.view', ['id'=>$request->addres_id]));
                    exit();
                }
                else { $_SESSION['errorMessages'] = $response->errors; }
            }

            if (!isset($request)) {
                $request = new AddRequest($_SESSION['USER']->id, $_GET);
            }
            return new Views\AddView(
                $request,
                $addressInfo,
                $this->di->get('Domain\Subunits\Metadata'),
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
    public function correct(array $params)
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

    public function verify  (array $p) { return $this->doBasicChangeLogUseCase('Verify'  ); }
    public function retire  (array $p) { return $this->doBasicChangeLogUseCase('Retire'  ); }
    public function unretire(array $p) { return $this->doBasicChangeLogUseCase('Unretire'); }

    /**
     * Standard use case handler involving a ChangeLogEntry
     *
     * The use case name should be the capitalized version, matching the
     * directory name in /src/Domain.
     *
     * @param string $name  The short (capitalized) use case name
     */
    private function doBasicChangeLogUseCase(string $name)
    {
        $useCase        = "Domain\\Subunits\\UseCases\\$name\\$name";
        $useCaseRequest = "Domain\\Subunits\\UseCases\\$name\\{$name}Request";
        $useCaseView    = "Application\\Subunits\\Views\\{$name}View";

        $subunit_id = !empty($_REQUEST['id']) ? (int)$_REQUEST['id'] : null;
        if ($subunit_id) {
            if (isset($_POST['id'])) {
                $request  = new $useCaseRequest($subunit_id, $_SESSION['USER']->id, $_POST);
                $handle   = $this->di->get($useCase);
                $response = $handle($request);

                if (!count($response->errors)) {
                    header('Location: '.View::generateUrl('subunits.view', ['id'=>$subunit_id]));
                    exit();
                }
                else { $_SESSION['errorMessages'] = $response->errors; }
            }


            $info    = parent::subunitInfo($subunit_id);
            $contact = !empty($_REQUEST['contact_id']) ? parent::person((int)$_REQUEST['contact_id']) : null;
            if (!isset($request)) {
                $request = new $useCaseRequest($subunit_id, $_SESSION['USER']->id, [
                    'contact_id' => $contact ? $contact->id : null
                ]);
            }

            return new $useCaseView($request, $info);
        }

        return new \Application\Views\NotFoundView();
    }
}
