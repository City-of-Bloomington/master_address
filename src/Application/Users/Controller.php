<?php
/**
 * @copyright 2012-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
namespace Application\Users;

use Application\Controller as BaseController;
use Application\View;

use Domain\Users\Entities\User;
use Domain\Users\UseCases\Info\InfoRequest;
use Domain\Users\UseCases\Search\SearchRequest;
use Domain\Users\UseCases\Update\UpdateRequest;
use Domain\Users\UseCases\Delete\DeleteRequest;

class Controller extends BaseController
{
    const ITEMS_PER_PAGE = 20;
    const DEFAULT_ROLE   = 'Public';
    const DEFAULT_AUTH   = 'local';

	public function index(array $params)
	{
        global $ZEND_ACL;

		$page   =  !empty($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = $this->di->get('Domain\Users\UseCases\Search\Search');
        $auth   = $this->di->get('Domain\Auth\AuthenticationService');
        $res    = $search(new SearchRequest($_GET, null, self::ITEMS_PER_PAGE, $page));

        return new Views\SearchView($res,
                                    self::ITEMS_PER_PAGE,
                                    $page,
                                    $ZEND_ACL->getRoles(),
                                    $auth->getAuthenticationMethods());
	}

	public function update(array $params)
	{

        if (isset($_POST['firstname'])) {
            $update   = $this->di->get('Domain\Users\UseCases\Update\Update');
            $request  = new UpdateRequest($_POST);
            if (!$request->role                 ) { $request->role                  = self::DEFAULT_ROLE; }
            if (!$request->authentication_method) { $request->authentication_method = self::DEFAULT_AUTH; }
            $response = $update($request);
            if (!count($response->errors)) {
                header('Location: '.View::generateUrl('users.index'));
                exit();
            }
            $user = new User((array)$request);
        }
        elseif (!empty($_REQUEST['id'])) {
            $info = $this->di->get('Domain\Users\UseCases\Info\Info');
            $req  = new InfoRequest((int)$_REQUEST['id']);
            try {
                $res  = $info($req);
                $user = $res->user;
            }
            catch (\Exception $e) {
                $_SESSION['errorMessages'] = $res->errors;
                return new \Application\Views\NotFoundView();
            }
        }
        else { $user = new User(); }

        global $ZEND_ACL;
        $auth = $this->di->get('Domain\Auth\AuthenticationService');
        return new Views\UpdateView($user,
                                    isset($response) ? $response : null,
                                    $ZEND_ACL->getRoles(),
                                    $auth->getAuthenticationMethods());
	}

	public function delete(array $params)
	{
        if (!empty($_REQUEST['id'])) {
            $delete = $this->di->get('Domain\Users\UseCases\Delete\Delete');
            $req    = new DeleteRequest((int)$_REQUEST['id']);
            $res    = $delete($req);
            if (count($res->errors)) {
                $_SESSION['errorMessages'] = $res->errors;
            }
        }
        else {
            $_SESSION['errorMessages'][] = 'users/unknown';
        }

		header('Location: '.View::generateUrl('users.index'));
		exit();
	}
}
