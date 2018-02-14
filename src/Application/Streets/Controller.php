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
    
    public function verify      (array $params) { return $this->doChangeLogUseCase('Verify'      ); }
    public function correct     (array $params) { return $this->doChangeLogUseCase('Correct'     ); }
    public function changeStatus(array $params) { return $this->doChangeLogUseCase('ChangeStatus'); }
    
    /**
     * @param  string $action  The use case name (case-sensitive)
     * @return View
     */
    private function doChangeLogUseCase(string $action)
    {
        
        $useCaseRequest = "Domain\\Streets\\UseCases\\$action\\{$action}Request";
        $useCaseView    = "Application\\Streets\\Views\\{$action}View";
        
        $user_id = $_SESSION['USER']->id;
        
        if (isset($_POST['id'])) {
            $request  = new $useCaseRequest((int)$_POST['id'], $user_id, $_POST);
            $useCase  = $this->di->get("Domain\\Streets\\UseCases\\$action\\$action");
            $response = $useCase($request);
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
                $request = new $useCaseRequest($res->street->id, $user_id);
            }
            catch (\Exception $e) { $_SESSION['errorMessages'] = $res->errors; }
        }
        
        if (isset($request)) {
            return new $useCaseView($request, $res);
        }
        
        return new \Application\Views\NotFoundView();
    }
}
