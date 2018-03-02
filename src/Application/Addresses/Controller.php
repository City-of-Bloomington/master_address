<?php
/**
 * @copyright 2017-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Addresses;

use Application\Controller as BaseController;
use Application\View;

use Domain\Addresses\UseCases\Parse\Parse;
use Domain\Addresses\UseCases\Parse\ParseResponse;
use Domain\Addresses\Entities\Address;
use Domain\Addresses\UseCases\Correct\CorrectRequest;
use Domain\Addresses\UseCases\Search\SearchRequest;
use Domain\Addresses\UseCases\Search\SearchResponse;
use Domain\Addresses\UseCases\Retire\RetireRequest;
use Domain\Addresses\UseCases\Verify\VerifyRequest;

class Controller extends BaseController
{
    const ITEMS_PER_PAGE = 20;

    /**
     * Converts Parser fieldnames to SearchRequest fieldnames
     */
    private static function translateFields(ParseResponse $parse): array
    {
        $query = [];
        foreach ($parse as $k=>$v) {
            if ($v) {
                switch ($k) {
                    case Parse::DIRECTION:      $query['street_direction'     ] = $v; break;
                    case Parse::STREET_NAME:    $query['street_name'          ] = $v; break;
                    case Parse::POST_DIRECTION: $query['street_post_direction'] = $v; break;
                    case Parse::STREET_TYPE:    $query['street_suffix_code'   ] = $v; break;
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
        $parser = $this->di->get('Domain\Addresses\UseCases\Parse\Parse');

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
        if (!empty($_GET['address'])) {
            $parser = $this->di->get('Domain\Addresses\UseCases\Parse\Parse');
            return new Views\ParseView($parser($_GET['address']));
        }
        return new Views\ParseView();
    }

    public function view(array $params)
    {
        if (!empty($_GET['id'])) {
            $info = $this->addressInfo((int)$_GET['id']);
            if ($info->address) {
                return new Views\InfoView($info);
            }
            else {
                $_SESSION['errorMessages'] = $info->errors;
            }
        }
        return new \Application\Views\NotFoundView();
    }

    /**
     * Correct an error in the primary attributes of an address
     */
    public function correct(array $params)
    {
        if (isset($_POST['id'])) {
            $request  = new CorrectRequest((int)$_POST['id'], $_SESSION['USER']->id, $_POST);
            $correct  = $this->di->get('Domain\Addresses\UseCases\Correct\Correct');
            $response = $correct($request);
            if (!count($response->errors)) {
                header('Location: '.View::generateUrl('addresses.view', ['id'=>$request->address_id]));
                exit();
            }
            else { $_SESSION['errorMessages'] = $response->errors; }
        }

        if (!empty($_REQUEST['id'])) {
            $info    = $this->addressInfo((int)$_REQUEST['id']);
            $street  = !empty($_REQUEST['street_id'])
                     ? $this->street((int)$_REQUEST['street_id'])
                     : $this->street($info->address->street_id);
            $request = new CorrectRequest($info->address->id, $_SESSION['USER']->id, (array)$info->address);
            return new Views\CorrectView($request, $info, $street);
        }
        return new \Application\Views\NotFoundView();
    }

    public function verify  (array $p) { return $this->doBasicChangeLogUseCase('Verify'  ); }
    public function retire  (array $p) { return $this->doBasicChangeLogUseCase('Retire'  ); }
    public function unretire(array $p) { return $this->doBasicChangeLogUseCase('Unretire'); }

    /**
     * Standard use case handler involving a ChangeLogEntry
     *
     * The use case name should be the capitalized version, matching the
     * directory name in /src/Domain.
     *
     * @param string $name  The short (capitalized) use case name
     */
    private function doBasicChangeLogUseCase(string $name)
    {
        $useCase        = "Domain\\Addresses\\UseCases\\$name\\$name";
        $useCaseRequest = "Domain\\Addresses\\UseCases\\$name\\{$name}Request";
        $useCaseView    = "Application\\Addresses\\Views\\{$name}View";

        if (isset($_POST['id'])) {
            $request  = new $useCaseRequest((int)$_POST['id'], $_SESSION['USER']->id, $_POST);
            $handle   = $this->di->get($useCase);
            $response = $handle($request);

            if (!count($response->errors)) {
                header('Location: '.View::generateUrl('addresses.view', ['id'=>$request->address_id]));
                exit();
            }
            else { $_SESSION['errorMessages'] = $response->errors; }
        }

        if (!empty($_REQUEST['id'])) {
            $address_id = (int)$_REQUEST['id'];

            return new $useCaseView(
                new $useCaseRequest($address_id, $_SESSION['USER']->id),
                $this->addressInfo( $address_id)
            );
        }

        return new \Application\Views\NotFoundView();
    }

    private function addressInfo(int $address_id): \Domain\Addresses\UseCases\Info\InfoResponse
    {
        $info = $this->di->get('Domain\Addresses\UseCases\Info\Info');
        $req  = new \Domain\Addresses\UseCases\Info\InfoRequest($address_id);
        return $info($req);
    }

    private function street(int $street_id): ?\Domain\Streets\Entities\Street
    {
        $load = $this->di->get('Domain\Streets\UseCases\Load\Load');
        $res  = $load($street_id);
        if ($res->errors) { $_SESSION['errorMessages'] = $res->errors; }
        return $res->street;
    }
}
