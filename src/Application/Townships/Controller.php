<?php
/**
 * @copyright 2017-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Townships;

use Application\View;

use Domain\Townships\Entities\Township;
use Domain\Townships\UseCases\Info\Info;
use Domain\Townships\UseCases\Info\InfoRequest;
use Domain\Townships\UseCases\Search\Search;
use Domain\Townships\UseCases\Search\SearchRequest;
use Domain\Townships\UseCases\Update\Update;
use Domain\Townships\UseCases\Update\UpdateRequest;

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
        $search = $this->di->get('Domain\Townships\UseCases\Search\Search');
        $res    = $search(new SearchRequest());

        return new Views\ListView($res);
    }

    public function update(array $params)
    {
        if (isset($_POST['name'])) {
            $update   = $this->di->get('Domain\Townships\UseCases\Update\Update');
            $request  = new UpdateRequest($_POST);
            $response = $update($request);
            if (!count($response->errors)) {
                header('Location: '.View::generateUrl('townships.index'));
                exit();
            }
            else {
                $_SESSION['errorMessages'] = $response->errors;
            }
            $township = new Township((array)$request);
        }
        elseif (!empty($_REQUEST['id'])) {
            $info = $this->di->get('Domain\Townships\UseCases\Info\Info');
            $req  = new InfoRequest((int)$_REQUEST['id']);
            try {
                $res      = $info($req);
                $township = $res->township;
            }
            catch (\Exception $e) {
                $_SESSION['errorMessages'] = $res->errors;
                return new \Application\Views\NotFoundView();
            }
        }
        else { $township = new Township(); }

        return new Views\UpdateView($township, isset($response) ? $response : null);
    }
}
