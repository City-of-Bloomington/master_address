<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Subunits\Controllers;

use Application\Subunits\Views\UpdateView;
use Domain\Subunits\UseCases\Update\Request as UpdateRequest;

use Application\Controller;
use Application\View;

class UpdateController extends Controller
{
    public function update(array $params): View
    {
        $subunit_id = !empty($_REQUEST['id']) ? (int)$_REQUEST['id'] : null;
        if ($subunit_id) {

            if (isset($_POST['notes'])) {
                $update   = $this->di->get('Domain\Subunits\UseCases\Update\Command');
                $request  = new UpdateRequest($subunit_id, $_SESSION['USER']->id, $_POST);
                $response = $update($request);
                if (!$response->errors) {
                    header('Location: '.View::generateUrl('subunits.view', ['id'=>$subunit_id]));
                    exit();
                }
                $_SESSION['errorMessages'] = $response->errors;
            }

            $info     = parent::subunitInfo($subunit_id);
            $location = $info->activeCurrentLocation();
            $contact  = !empty($_REQUEST['contact_id']) ? parent::person((int)$_REQUEST['contact_id']) : null;
            if (!isset($request)) {
                $request = new UpdateRequest($subunit_id, $_SESSION['USER']->id, [
                    'notes'           => $info->subunit->notes,
                    'locationType_id' => $location ? $location->type_id       : null,
                    'mailable'        => $location ? $location->mailable      : null,
                    'occupiable'      => $location ? $location->occupiable    : null,
                    'group_quarter'   => $location ? $location->group_quarter : null
                ]);
            }
            return new UpdateView($request,
                                  $this->di->get('Domain\Locations\Metadata'),
                                  $info,
                                  $contact);
        }
        return new \Application\Views\NotFoundView();
    }
}
