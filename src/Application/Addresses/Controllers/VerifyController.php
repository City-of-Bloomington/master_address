<?php
/**
 * Create a changeLog entry, declaring you have verified this address
 *
 * @copyright 2020 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Addresses\Controllers;

use Application\Controller;
use Application\View;
use Application\Views\NotFoundView;

use Application\Addresses\Views\VerifyView;
use Domain\Addresses\UseCases\Verify\VerifyRequest;

class VerifyController extends Controller
{
    public function verify(array $params): View
    {
        $address_id = !empty($_REQUEST['id']) ? (int)$_REQUEST['id'] : null;
        if ($address_id) {
            $request = new VerifyRequest($address_id, $_SESSION['USER']->id, $_REQUEST);

            if (isset($_POST['id'])) {
                $verify   = $this->di->get('Domain\Addresses\UseCases\Verify\Verify');
                $response = $verify($request);
                if (!$response->errors) {
                    header('Location: '.View::generateUrl('addresses.view', ['id'=>$address_id]));
                    exit();
                }
                $_SESSION['errorMessages'] = $response->errors;
            }

            $info     = parent::addressInfo($address_id);
            $contact  = !empty($_REQUEST['contact_id']) ? parent::person((int)$_REQUEST['contact_id']) : null;
            return new VerifyView($request, $info, $contact);
        }
        return new NotFoundView();
    }
}
