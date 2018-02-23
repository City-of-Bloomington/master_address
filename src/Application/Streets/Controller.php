<?php
/**
 * @copyright 2017-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Streets;

use Application\Controller as BaseController;
use Application\View;

use Domain\Addresses\UseCases\Parse\Parse;
use Domain\Addresses\UseCases\Parse\ParseResponse;
use Domain\Streets\Entities\Street;
use Domain\Streets\UseCases\Correct\CorrectRequest;
use Domain\Streets\UseCases\Search\SearchRequest;
use Domain\Streets\UseCases\Search\SearchResponse;
use Domain\Streets\UseCases\Verify\VerifyRequest;

class Controller extends BaseController
{
    const ITEMS_PER_PAGE = 20;

    /**
     * Converts Parser fieldnames to SearchRequest fieldnames
     */
    public static function extractStreetFields(ParseResponse $parse): array
    {
        $query = [];
        if (!empty($parse->{Parse::DIRECTION     })) { $query['direction'     ] = $parse->{Parse::DIRECTION     }; }
        if (!empty($parse->{Parse::STREET_NAME   })) { $query['name'          ] = $parse->{Parse::STREET_NAME   }; }
        if (!empty($parse->{Parse::POST_DIRECTION})) { $query['post_direction'] = $parse->{Parse::POST_DIRECTION}; }
        if (!empty($parse->{Parse::STREET_TYPE   })) { $query['suffix_code'   ] = $parse->{Parse::STREET_TYPE   }; }
        return $query;
    }

    public function index(array $params)
    {
		$page   =  !empty($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = $this->di->get('Domain\Streets\UseCases\Search\Search');
        $parser = $this->di->get('Domain\Addresses\UseCases\Parse\Parse');

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
            $info = $this->streetInfo((int)$_REQUEST['id']);

            if ($info->street) {
                return new Views\InfoView($info, $this->addressSearch($info->street->id));
            }
            else {
                $_SESSION['errorMessages'] = $info->errors;
            }
        }
        return new \Application\Views\NotFoundView();
    }
    
    /**
     * Declare that a street is correct at the current time
     */
    public function verify(array $params)
    {
        if (isset($_POST['id'])) {
            $request  = new VerifyRequest((int)$_POST['id'], $_SESSION['USER']->id, $_POST);
            $verify   = $this->di->get('Domain\Streets\UseCases\Verify\Verify');
            $response = $verify($request);
            
            if (!count($response->errors)) {
                header('Location: '.View::generateUrl('streets.view', ['id'=>$request->street_id]));
                exit();
            }
            else { $_SESSION['errorMessages'] = $response->errors; }
        }
        
        if (!empty($_REQUEST['id'])) {
            $street_id = (int)$_REQUEST['id'];
            
            return new Views\VerifyView(
                new VerifyRequest(   $street_id, $_SESSION['USER']->id),
                $this->streetInfo(   $street_id),
                $this->addressSearch($street_id)
            );
        }
        
        return new \Application\Views\NotFoundView();
    }
    
    /**
     * Correct an error in the primary attributes of a street
     */
    public function correct(array $params)
    {
        if (isset($_POST['id'])) {
            $request  = new CorrectRequest((int)$_POST['id'], $_SESSION['USER']->id, $_POST);
            $correct  = $this->di->get('Domain\Streets\UseCases\Correct\Correct');
            $response = $correct($request);
            if (!count($response->errors)) {
                header('Location: '.View::generateUrl('streets.view', ['id'=>$request->street_id]));
                exit();
            }
            else { $_SESSION['errorMessages'] = $response->errors; }
        }
        
        if (!empty($_REQUEST['id'])) {
            $street_id = (int)$_REQUEST['id'];
            $info      = $this->streetInfo($street_id);
            return new Views\CorrectView(
                new CorrectRequest($street_id, $_SESSION['USER']->id, (array)$info->street),
                $info,
                $this->di->get('Domain\Streets\Metadata'),
                $this->addressSearch($street_id)
            );
        }
        return new \Application\Views\NotFoundView();
    }
    
    private function streetInfo(int $street_id): \Domain\Streets\UseCases\Info\InfoResponse
    {
        $info = $this->di->get('Domain\Streets\UseCases\Info\Info');
        $req  = new \Domain\Streets\UseCases\Info\InfoRequest($street_id);
        return $info($req);
    }
    
    private function addressSearch(int $street_id): \Domain\Addresses\UseCases\Search\SearchResponse
    {
        $search = $this->di->get('Domain\Addresses\UseCases\Search\Search');
        return $search(new \Domain\Addresses\UseCases\Search\SearchRequest(['street_id' => $street_id]));
    }
}
