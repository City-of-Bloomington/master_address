<?php
/**
 * @copyright 2017-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Addresses;

use Application\Controller as BaseController;
use Application\View;

use Domain\Addresses\Parser;
use Domain\Addresses\Entities\Address;
use Domain\Addresses\UseCases\Info\InfoRequest;
use Domain\Addresses\UseCases\Search\SearchRequest;
use Domain\Addresses\UseCases\Search\SearchResponse;
use Domain\Addresses\UseCases\Verify\VerifyRequest;

use Domain\ChangeLogs\ChangeLogRequest;

class Controller extends BaseController
{
    const ITEMS_PER_PAGE = 20;

    /**
     * Converts Parser fieldnames to SearchRequest fieldnames
     */
    private static function translateFields(array $parse): array
    {
        $query = [];
        foreach ($parse as $k=>$v) {
            if (!empty($v)) {
                switch ($k) {
                    case Parser::DIRECTION:      $query['street_direction'     ] = $v; break;
                    case Parser::STREET_NAME:    $query['street_name'          ] = $v; break;
                    case Parser::POST_DIRECTION: $query['street_post_direction'] = $v; break;
                    case Parser::STREET_TYPE:    $query['street_suffix_code'   ] = $v; break;
                    default:
                        $query[$k] = $v;
                }
            }
        }
        return $query;
    }

    public function index(array $params)
    {
		$page   =  !empty($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = $this->di->get('Domain\Addresses\UseCases\Search\Search');
        $parser = $this->di->get('Domain\Addresses\Parser');

        $query  = !empty($_GET['address'])
                ? self::translateFields($parser($_GET['address']))
                : null;
        $res    = $query
                ? $search(new SearchRequest($query, null, self::ITEMS_PER_PAGE, $page))
                : new SearchResponse();

        return new Views\SearchView($res, self::ITEMS_PER_PAGE, $page);
    }

    public function parse(array $params)
    {
    }

    public function view(array $params)
    {
        if (!empty($_GET['id'])) {
            $addressInfo = $this->di->get('Domain\Addresses\UseCases\Info\Info');

            $info = $addressInfo(new InfoRequest((int)$_GET['id']));
            if ($info->address) {
                return new Views\InfoView($info);
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
            $request  = new VerifyRequest((int)$_POST['id'], $user_id, $_POST['notes']);
            $verify   = $this->di->get('Domain\Addresses\UseCases\Verify\Verify');
            $response = $verify($request);
            if (!count($response->errors)) {
                header('Location: '.View::generateUrl('addresses.view', ['id'=>$request->address_id]));
                exit();
            }
            else { $_SESSION['errorMessages'] = $response->errors; }
        }
        else if (!empty($_REQUEST['id'])) {
            $info = $this->di->get('Domain\Addresses\UseCases\Info\Info');
            $req  = new InfoRequest((int)$_REQUEST['id']);
            try {
                $res = $info($req);
                $request = new VerifyRequest($res->address->id, $user_id);
            }
            catch (\Exception $e) { $_SESSION['errorMessages'] = $res->errors; }
        }
        
        if (isset($request)) {
            return new Views\VerifyView($request, $res);
        }
        
        return new \Application\Views\NotFoundView();
    }
}
