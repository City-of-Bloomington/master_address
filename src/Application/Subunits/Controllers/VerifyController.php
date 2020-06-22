<?php
/**
 * Create a changeLog comment, declaring you have verified this subunit.
 *
 * @copyright 2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Subunits\Controllers;

use Application\Subunits\Views\VerifyView;
use Domain\Subunits\UseCases\Verify\VerifyRequest;

use Application\Controller;
use Application\View;

class VerifyController extends Controller
{
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

            return new VerifyView(
                $request,
                parent::subunitInfo($subunit_id),
                !empty($_REQUEST['contact_id']) ? parent::person((int)$_REQUEST['contact_id']) : null
            );
        }
        return new \Application\Views\NotFoundView();
    }
}
