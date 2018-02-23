<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Subunits;

use Application\Controller as BaseController;
use Application\View;

use Domain\Subunits\UseCases\Verify\VerifyRequest;

class Controller extends BaseController
{
    public function view(array $params)
    {
        if (!empty($_GET['id'])) {
            $info = $this->subunitInfo((int)$_GET['id']);
            if ($info->subunit) {
                return new Views\InfoView($info);
            }
            else {
                $_SESSION['errorMessages'] = $info->errors;
            }
        }
        return new \Application\Views\NotFoundView();
    }

    /**
     * Declare that a subunit is correct at the current time
     */
    public function verify(array $params)
    {
        if (isset($_POST['id'])) {
            $request  = new VerifyRequest((int)$_POST['id'], $_SESSION['USER']->id, $_POST);
            $verify   = $this->di->get('Domain\Subunits\UseCases\Verify\Verify');
            $response = $verify($request);

            if (!count($response->errors)) {
                header('Location: '.View::generateUrl('subunits.view', ['id'=>$request->subunit_id]));
                exit();
            }
            else { $_SESSION['errorMessages'] = $response->errors; }
        }

        if (!empty($_REQUEST['id'])) {
            $subunit_id = (int)$_REQUEST['id'];

            return new Views\VerifyView(
                new VerifyRequest(   $subunit_id, $_SESSION['USER']->id),
                $this->subunitInfo(  $subunit_id)
            );
        }

        return new \Application\Views\NotFoundView();
    }

    private function subunitInfo(int $subunit_id): \Domain\Subunits\UseCases\Info\InfoResponse
    {
        $info = $this->di->get('Domain\Subunits\UseCases\Info\Info');
        $req  = new \Domain\Subunits\UseCases\Info\InfoRequest($subunit_id);
        return $info($req);
    }
}
