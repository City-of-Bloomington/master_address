<?php
/**
 * @copyright 2017-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Towns;

use Application\Controller as BaseController;
use Application\View;

use Domain\Towns\Entities\Town;
use Domain\Towns\UseCases\Info\InfoRequest;
use Domain\Towns\UseCases\Search\SearchRequest;
use Domain\Towns\UseCases\Update\UpdateRequest;


class Controller extends BaseController
{
    public function index(array $params)
    {
        $search = $this->di->get('Domain\Towns\UseCases\Search\Search');
        $res    = $search(new SearchRequest());

        return new Views\ListView($res);
    }

    public function update(array $params)
    {
        if (isset($_POST['name'])) {
            $update   = $this->di->get('Domain\Towns\UseCases\Update\Update');
            $request  = new UpdateRequest($_POST);
            $response = $update($request);
            if (!count($response->errors)) {
                header('Location: '.View::generateUrl('towns.index'));
                exit();
            }
            $town = new Town((array)$request);
        }
        elseif (!empty($_REQUEST['id'])) {
            $info = $this->di->get('Domain\Towns\UseCases\Info\Info');
            $req  = new InfoRequest((int)$_REQUEST['id']);
            try {
                $res  = $info($req);
                $town = $res->town;
            }
            catch (\Exception $e) {
                $_SESSION['errorMessages'] = $res->errors;
                return new \Application\Views\NotFoundView();
            }
        }
        else { $town = new Town(); }

        return new Views\UpdateView($town, isset($response) ? $response : null);
    }
}
