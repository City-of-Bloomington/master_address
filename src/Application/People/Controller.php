<?php
/**
 * @copyright 2012-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\People;

use Application\Controller as BaseController;
use Application\View;

use Domain\People\Entities\Person;
use Domain\People\UseCases\Info\InfoRequest;
use Domain\People\UseCases\Search\SearchRequest;
use Domain\People\UseCases\Update\UpdateRequest;

class Controller extends BaseController
{
	public function index(array $params)
	{
		$page   =  !empty($_GET['page']) ? (int)$_GET['page'] : 1;
		$query  = (!empty($_GET['firstname']) || !empty($_GET['lastname']) || !empty($_GET['email'])) ? $_GET : null;

        $search = $this->di->get('Domain\People\UseCases\Search\Search');
        $res    = $search(new SearchRequest($query, null, Views\SearchView::ITEMS_PER_PAGE, $page));

        return new Views\SearchView($res, $page);
	}

	public function view(array $params)
	{
        if (!empty($_REQUEST['id'])) {
            $info = $this->di->get('Domain\People\UseCases\Info\Info');
            $req  = new InfoRequest((int)$_REQUEST['id']);
            $res  = $info($req);
            if ($res->person) {
                return new Views\InfoView($res);
            }
            else {
                $_SESSION['errorMessages'] = $res->errors;
            }
        }
        return new \Application\Views\NotFoundView();
	}

	public function update(array $params)
	{
        if (!empty($_REQUEST['return_url'])) {
            $_SESSION['return_url'] = urldecode($_REQUEST['return_url']);
        }

        if (isset($_POST['firstname'])) {
            $update   = $this->di->get('Domain\People\UseCases\Update\Update');
            $request  = new UpdateRequest($_POST);
            $response = $update($request);
            if (!count($response->errors)) {
                $return_url = !empty($_SESSION['return_url'])
                            ? $_SESSION['return_url']
                            : ($person->getId()
                                ? parent::generateUrl('people.view', ['id'=>$response->id])
                                : parent::generateUrl('people.view'));
                unset($_SESSION['return_url']);
                header('Location: '.$return_url);
                exit();
            }
            $person = new Person((array)$request);
        }
        elseif (!empty($_REQUEST['id'])) {
            $info = $this->di->get('Domain\People\UseCases\Info\Info');
            $req  = new InfoRequest((int)$_REQUEST['id']);
            try {
                $res    = $info($req);
                $person = $res->person;
            }
            catch (\Exception $e) {
                $_SESSION['errorMessages'] = $res->errors;
                return new \Application\Views\NotFoundView();
            }
        }
        else { $person = new Person(); }

        return new Views\UpdateView($person, isset($response) ? $response : null);
	}
}
