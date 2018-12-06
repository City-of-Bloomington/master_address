<?php
/**
 * @copyright 2017-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Subdivisions;

use Application\Controller as BaseController;
use Application\View;

use Domain\Subdivisions\UseCases\Info\InfoRequest;
use Domain\Subdivisions\UseCases\Search\SearchRequest;
use Domain\Subdivisions\UseCases\Update\UpdateRequest;

class Controller extends BaseController
{
    const ITEMS_PER_PAGE = 20;

    public function index(array $params)
    {
		$page   =  !empty($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = $this->di->get('Domain\Subdivisions\UseCases\Search\Search');
        $res    = $search(new SearchRequest($_GET, null, self::ITEMS_PER_PAGE, $page));

        return new Views\SearchView($res, self::ITEMS_PER_PAGE, $page);
    }

	public function view(array $params)
	{
        if (!empty($_REQUEST['id'])) {
            $info = $this->di->get('Domain\Subdivisions\UseCases\Info\Info');
            $req  = new InfoRequest((int)$_REQUEST['id']);
            $res  = $info($req);
            if ($res->subdivision) {
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
        if (isset($_POST['name'])) {
            $update   = $this->di->get('Domain\Subdivisions\UseCases\Update\Update');
            $request  = new UpdateRequest($_POST);
            $response = $update($request);
            if (!count($response->errors)) {
                header('Location: '.View::generateUrl('subdivisions.view', ['id'=>$response->id]));
                exit();
            }
        }
        elseif (!empty($_REQUEST['id'])) {
            $info = $this->di->get('Domain\Subdivisions\UseCases\Info\Info');
            $req  = new InfoRequest((int)$_REQUEST['id']);
            try {
                $res  = $info($req);
                $request = new UpdateRequest((array)$res->subdivision);
            }
            catch (\Exception $e) {
                $_SESSION['errorMessages'] = $res->errors;
                return new \Application\Views\NotFoundView();
            }
        }
        else { $request = new UpdateRequest(); }

        $metadata = $this->di->get('Domain\Subdivisions\Metadata');

        return new Views\UpdateView($request, $metadata, isset($response) ? $response : null);
    }
}
