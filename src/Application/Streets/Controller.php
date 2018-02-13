<?php
/**
 * @copyright 2017-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Streets;

use Application\Controller as BaseController;
use Application\View;

use Domain\Addresses\Parser;
use Domain\Streets\Entities\Street;
use Domain\Streets\UseCases\Info\InfoRequest;
use Domain\Streets\UseCases\Search\SearchRequest;
use Domain\Streets\UseCases\Search\SearchResponse;
use Domain\Streets\UseCases\Verify\VerifyRequest;

class Controller extends BaseController
{
    const ITEMS_PER_PAGE = 20;

    /**
     * Converts Parser fieldnames to SearchRequest fieldnames
     */
    public static function extractStreetFields(array $parse): array
    {
        $query = [];
        if (!empty($parse[Parser::DIRECTION     ])) { $query['direction'     ] = $parse[Parser::DIRECTION     ]; }
        if (!empty($parse[Parser::STREET_NAME   ])) { $query['name'          ] = $parse[Parser::STREET_NAME   ]; }
        if (!empty($parse[Parser::POST_DIRECTION])) { $query['post_direction'] = $parse[Parser::POST_DIRECTION]; }
        if (!empty($parse[Parser::STREET_TYPE   ])) { $query['suffix_code'   ] = $parse[Parser::STREET_TYPE   ]; }
        return $query;
    }

    public function index(array $params)
    {
		$page   =  !empty($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = $this->di->get('Domain\Streets\UseCases\Search\Search');
        $parser = $this->di->get('Domain\Addresses\Parser');

        $query  = !empty($_GET['street'])
                ? self::extractStreetFields($parser($_GET['street']))
                : null;
        $res    = $query
                ? $search(new SearchRequest($query, null, self::ITEMS_PER_PAGE, $page))
                : new SearchResponse();

        return new Views\SearchView($res, self::ITEMS_PER_PAGE, $page);
    }

    public function view(array $params)
    {
        if (!empty($_REQUEST['id'])) {
            $streetInfo    = $this->di->get('Domain\Streets\UseCases\Info\Info');
            $addressSearch = $this->di->get('Domain\Addresses\UseCases\Search\Search');

            $infoRequest = new InfoRequest((int)$_REQUEST['id']);

            $info = $streetInfo($infoRequest);
            if ($info->street) {
                $addresses = $addressSearch(new \Domain\Addresses\UseCases\Search\SearchRequest([
                    'street_id'=>$info->street->id
                ]));
                return new Views\InfoView($info, $addresses);
            }
            else {
                $_SESSION['errorMessages'] = $info->errors;
            }
        }
        return new \Application\Views\NotFoundView();
    }

    public function verify(array $params)
    {
        $user_id = $_SESSION['USER']->id;
        
        if (isset($_POST['id'])) {
            echo "Posting...\n";
            $request  = new VerifyRequest((int)$_POST['id'], $user_id, $_POST['notes']);
            $verify   = $this->di->get('Domain\Streets\UseCases\Verify\Verify');
            $response = $verify($request);
            if (!count($response->errors)) {
                header('Location: '.View::generateUrl('streets.view', ['id'=>$request->street_id]));
                exit();
            }
            else { $_SESSION['errorMessages'] = $response->errors; }
        }
        else if (!empty($_REQUEST['id'])) {
            $info = $this->di->get('Domain\Streets\UseCases\Info\Info');
            $req  = new InfoRequest((int)$_REQUEST['id']);
            try {
                $res = $info($req);
                $request = new VerifyRequest($res->street->id, $user_id);
            }
            catch (\Exception $e) { $_SESSION['errorMessages'] = $res->errors; }
        }
        
        if (isset($request)) {
            return new Views\VerifyView($request, $res);
        }
        
        exit();
        return new \Application\Views\NotFoundView();
    }

    public function correct(array $params)
    {
        if (!empty($_REQUEST['id'])) {
            try { $street  = new Street($_REQUEST['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }

        if (isset($street)) {
            if (isset($_POST['id'])) {
                $correction = new Messages\CorrectRequest($street, $_SESSION['USER'], $_POST);
                try {
                    $street->correct($correction);
                    header('Location: '.View::generateUrl('streets.view', ['id'=>$street->getId()]));
                    exit();
                }
                catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
            }
            else {
                $correction = new Messages\CorrectRequest($street, $_SESSION['USER']);
            }
            return new Views\Actions\CorrectView(['request'=>$correction]);
        }
        return new \Application\Views\NotFoundView();
    }

    public function changeStatus(array $params)
    {
        if (!empty($_REQUEST['id'])) {
            try { $street  = new Street($_REQUEST['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }

        if (isset($street)) {
            if (isset($_POST['id'])) {
                $change = new Messages\StatusChangeRequest($street, $_SESSION['USER'], $_POST);
                try {
                    $street->changeStatus($change);
                    header('Location: '.View::generateUrl('streets.view', ['id'=>$street->getId()]));
                    exit();
                }
                catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
            }
            else {
                $change = new Messages\StatusChangeRequest($street, $_SESSION['USER']);
            }
            return new Views\Actions\StatusChangeView(['request'=>$change]);
        }
        return new \Application\Views\NotFoundView();
    }
}
