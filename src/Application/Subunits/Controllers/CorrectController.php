<?php
/**
 * Correct an error in the primary attributes of a subunit
 *
 * @copyright 2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Subunits\Controllers;

use Application\Subunits\Views\CorrectView;
use Domain\Subunits\UseCases\Correct\CorrectRequest;

use Application\Controller;
use Application\View;

class CorrectController extends Controller
{
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
            return new CorrectView(
                $request,
                $info,
                $this->di->get('Domain\Subunits\Metadata'),
                $contact
            );
        }
        return new \Application\Views\NotFoundView();
    }
}
