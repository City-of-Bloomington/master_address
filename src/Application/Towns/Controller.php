<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Towns;

use Blossom\Classes\Database;
use Application\View;

use Domain\Towns\Entities\Town;

use Domain\Towns\UseCases\Info\Info;
use Domain\Towns\UseCases\Info\InfoRequest;
use Domain\Towns\UseCases\Search\Search;
use Domain\Towns\UseCases\Search\SearchRequest;
use Domain\Towns\UseCases\Update\Update;
use Domain\Towns\UseCases\Update\UpdateRequest;


class Controller
{
    private $di;

    public function __construct()
    {
        global $DI;
        $this->di = $DI;
    }

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
            else {
                $_SESSION['errorMessages'] = $response->errors;
            }
            $town = new Town(['id'=>$request->id, 'name'=>$request->name]);
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
