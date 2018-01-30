<?php
/**
 * @copyright 2017-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Plats;

use Application\Controller as BaseController;
use Application\View;

use Domain\Plats\Entities\Plat;
use Domain\Plats\UseCases\Info\InfoRequest;
use Domain\Plats\UseCases\Search\SearchRequest;
use Domain\Plats\UseCases\Update\UpdateRequest;
use Domain\Plats\UseCases\Update\UpdateResponse;

class Controller extends BaseController
{
    const ITEMS_PER_PAGE = 20;

    public function index(array $params)
    {
		$page   =  !empty($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = $this->di->get('Domain\Plats\UseCases\Search\Search');
        $res    = $search(new SearchRequest($_GET, null, self::ITEMS_PER_PAGE, $page));

        return new Views\SearchView($res, self::ITEMS_PER_PAGE, $page);
    }

	public function view(array $params)
	{
        if (!empty($_REQUEST['id'])) {
            $info = $this->di->get('Domain\Plats\UseCases\Info\Info');
            $req  = new InfoRequest((int)$_REQUEST['id']);
            $res  = $info($req);
            if ($res->plat) {
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
        $errors = [];
        if (!empty($_POST['start_date'])) {
            try {  $_POST['start_date'] = new \DateTime($_POST['start_date']); }
            catch (\Exception $e) {
                $errors[] = 'invalidDate';
            }
        }
        if (!empty($_POST['end_date'])) {
            try {  $_POST['end_date'] = new \DateTime($_POST['end_date']); }
            catch (\Exception $e) { $errors[] = 'invalidDate'; }
        }

        if (isset($_POST['name'])) {
            if (!count($errors)) {
                $update   = $this->di->get('Domain\Plats\UseCases\Update\Update');
                $request  = new UpdateRequest($_POST);
                $response = $update($request);
                if (!count($response->errors)) {
                    header('Location: '.View::generateUrl('plats.view', ['id'=>$response->id]));
                    exit();
                }
                else {
                    $errors = $response->errors;
                }
            }
        }
        elseif (!empty($_REQUEST['id'])) {
            $info = $this->di->get('Domain\Plats\UseCases\Info\Info');
            $req  = new InfoRequest((int)$_REQUEST['id']);
            try {
                $res  = $info($req);
                $request = new UpdateRequest((array)$res->plat);
            }
            catch (\Exception $e) {
                $_SESSION['errorMessages'] = $res->errors;
                return new \Application\Views\NotFoundView();
            }
        }
        else { $request = new UpdateRequest(); }

        $metadata = $this->di->get('Domain\Plats\Metadata');

        return new Views\UpdateView($request, $metadata, $errors);
    }
}
