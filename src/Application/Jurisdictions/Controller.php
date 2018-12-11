<?php
/**
 * @copyright 2017-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Jurisdictions;

use Application\Controller as BaseController;
use Application\View;

use Domain\Jurisdictions\Entities\Jurisdiction;
use Domain\Jurisdictions\UseCases\Info\InfoRequest;
use Domain\Jurisdictions\UseCases\Search\SearchRequest;
use Domain\Jurisdictions\UseCases\Update\UpdateRequest;


class Controller extends BaseController
{
    public function index(array $params)
    {
        $search = $this->di->get('Domain\Jurisdictions\UseCases\Search\Search');
        $res    = $search(new SearchRequest());

        return new Views\ListView($res);
    }

    public function update(array $params)
    {
        if (isset($_POST['name'])) {
            $update   = $this->di->get('Domain\Jurisdictions\UseCases\Update\Update');
            $request  = new UpdateRequest($_POST);
            $response = $update($request);
            if (!count($response->errors)) {
                header('Location: '.View::generateUrl('jurisdictions.index'));
                exit();
            }
            else {
                $_SESSION['errorMessages'] = $response->errors;
            }
            $jurisdiction = new Jurisdiction((array)$request);
        }
        elseif (!empty($_REQUEST['id'])) {
            $info = $this->di->get('Domain\Jurisdictions\UseCases\Info\Info');
            $req  = new InfoRequest((int)$_REQUEST['id']);
            try {
                $res  = $info($req);
                $jurisdiction = $res->jurisdiction;
            }
            catch (\Exception $e) {
                $_SESSION['errorMessages'] = $res->errors;
                return new \Application\Views\NotFoundView();
            }
        }
        else { $jurisdiction = new Jurisdiction(); }

        return new Views\UpdateView($jurisdiction, isset($response) ? $response : null);
    }
}
