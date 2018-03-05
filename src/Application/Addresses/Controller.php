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
            $info = parent::addressInfo((int)$_GET['id']);
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
        $address_id = !empty($_REQUEST['id']) ? (int)$_REQUEST['id'] : null;
        if ($address_id) {
            if (isset($_POST['id'])) {
                $request  = new CorrectRequest($address_id, $_SESSION['USER']->id, $_POST);
                $correct  = $this->di->get('Domain\Addresses\UseCases\Correct\Correct');
                $response = $correct($request);
                if (!count($response->errors)) {
                    header('Location: '.View::generateUrl('addresses.view', ['id'=>$address_id]));
                    exit();
                }
                else { $_SESSION['errorMessages'] = $response->errors; }
            }

            $info    = parent::addressInfo($address_id);
            $street  = !empty($_REQUEST['street_id' ]) ? parent::street((int)$_REQUEST['street_id' ]) : parent::street($info->address->street_id);
            $contact = !empty($_REQUEST['contact_id']) ? parent::person((int)$_REQUEST['contact_id']) : null;
            if (!isset($request)) {
                $request = new CorrectRequest($address_id, $_SESSION['USER']->id, [
                    'street_id'            => $street->id,
                    'street_number_prefix' => $info->address->street_number_prefix,
                    'street_number'        => $info->address->street_number,
                    'street_number_suffix' => $info->address->street_number_suffix,
                    'zip'                  => $info->address->zip,
                    'zipplus4'             => $info->address->zipplus4,
                    'notes'                => $info->address->notes,
                    'contact_id'           => $contact ? $contact->id : null
                ]);
            }
            return new Views\CorrectView($request, $info, $street, $contact);
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

        $address_id = !empty($_REQUEST['id']) ? (int)$_REQUEST['id'] : null;
        if ($address_id) {
            if (isset($_POST['id'])) {
                $request  = new $useCaseRequest($address_id, $_SESSION['USER']->id, $_POST);
                $handle   = $this->di->get($useCase);
                $response = $handle($request);

                if (!count($response->errors)) {
                    header('Location: '.View::generateUrl('addresses.view', ['id'=>$request->address_id]));
                    exit();
                }
                else { $_SESSION['errorMessages'] = $response->errors; }
            }

            $info    = parent::addressInfo($address_id);
            $contact = !empty($_GET['contact_id']) ? parent::person((int)$_GET['contact_id']) : null;
            if (!isset($request)) {
                $request = new $useCaseRequest($address_id, $_SESSION['USER']->id, [
                    'contact_id' => $contact ? $contact->id : null
                ]);
            }

            return new $useCaseView($request, $info, $contact);
        }

        return new \Application\Views\NotFoundView();
    }
}
