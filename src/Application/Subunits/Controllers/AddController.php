<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Subunits\Controllers;

use Application\Subunits\Views\AddView;
use Domain\Subunits\UseCases\Add\AddRequest;

use Application\Controller;
use Application\View;

class AddController extends Controller
{
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

            return new AddView(
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
}
