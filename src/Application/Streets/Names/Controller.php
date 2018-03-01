<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);

namespace Application\Streets\Names;

use Application\Controller as BaseController;
use Application\Streets\Controller as StreetController;
use Domain\Streets\Names\UseCases\Correct\CorrectRequest;
use Domain\Streets\Names\UseCases\Search\SearchRequest;
use Domain\Streets\Names\UseCases\Search\SearchResponse;

class Controller extends BaseController
{
    const ITEMS_PER_PAGE = 20;

    public function index()
    {
		$page   =  !empty($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = $this->di->get('Domain\Streets\Names\UseCases\Search\Search');
        $parser = $this->di->get('Domain\Addresses\UseCases\Parse\Parse');

        $query  = !empty($_GET['street'])
                ? StreetController::extractStreetFields($parser($_GET['street']))
                : null;
        $res    = $query
                ? $search(new SearchRequest($query, null, self::ITEMS_PER_PAGE, $page))
                : new SearchResponse();

        return new Views\SearchView($res, self::ITEMS_PER_PAGE, $page);
    }

    public function view()
    {
        if (!empty($_GET['id'])) {
            $info = $this->nameInfo((int)$_GET['id']);
            if ($info->name) {
                return new Views\InfoView($info);
            }
            else {
                $_SESSION['errorMessages'] = $info->errors;
            }
        }
        return new \Application\Views\NotFoundView();
    }

    public function correct()
    {
        if (isset($_POST['id'])) {
            $request  = new CorrectRequest($_POST);
            $correct  = $this->di->get('Domain\Streets\Names\UseCases\Correct\Correct');
            $response = $correct($request);
            if (!count($response->errors)) {
                header('Location: '.View::generateUrl('streetNames.view', ['id'=>$request->id]));
                exit();
            }
            else { $_SESSION['errorMessages'] = $response->errors; }
        }

        if (!empty($_REQUEST['id'])) {
            $info = $this->nameInfo((int)$_REQUEST['id']);
            return new Views\CorrectView(
                new CorrectRequest((array)$info->name),
                $info,
                $this->di->get('Domain\Streets\Metadata')
            );
        }
        return new \Application\Views\NotFoundView();
    }

    private function nameInfo(int $name_id)
    {
        $info = $this->di->get('Domain\Streets\Names\UseCases\Info\Info');
        $req  = new \Domain\Streets\Names\UseCases\Info\InfoRequest($name_id);
        return $info($req);
    }
}
