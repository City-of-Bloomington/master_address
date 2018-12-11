<?php
/**
 * @copyright 2017-2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Addresses;

use Application\Controller as BaseController;
use Application\Url;
use Application\View;

use Domain\Addresses\UseCases\Parse\Parse;
use Domain\Addresses\UseCases\Parse\ParseResponse;
use Domain\Addresses\UseCases\Add\AddRequest;
use Domain\Addresses\UseCases\ChangeLog\ChangeLogRequest;
use Domain\Addresses\UseCases\ChangeStatus\ChangeStatusRequest;
use Domain\Addresses\UseCases\Correct\CorrectRequest;
use Domain\Addresses\UseCases\Readdress\ReaddressRequest;
use Domain\Addresses\UseCases\Search\SearchRequest;
use Domain\Addresses\UseCases\Search\SearchResponse;
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

    /**
     * Address search
     */
    public function index(array $params): View
    {
		$page   = !empty($_GET['page']) ? (int)$_GET['page'] : 1;

        $search = $this->di->get('Domain\Addresses\UseCases\Search\Search');
        $parser = $this->di->get('Domain\Addresses\UseCases\Parse\Parse');

        $query  = !empty($_GET['address'])
                ? self::translateFields($parser($_GET['address']))
                : null;
        $res    = $query
                ? $search(new SearchRequest($query, null, self::ITEMS_PER_PAGE, $page))
                : new SearchResponse();

        if (View::isAllowed('addresses', 'changeLog')) {
            $logPage   = !empty($_GET['logPage']) ? (int)$_GET['logPage'] : 1;
            $log       = $this->di->get('Domain\Addresses\UseCases\ChangeLog\ChangeLog');
            $changeLog = $log(new ChangeLogRequest(null, self::ITEMS_PER_PAGE, $logPage));
            return new Views\SearchView($res, self::ITEMS_PER_PAGE, $page, $changeLog, $logPage);
        }
        return new Views\SearchView($res, self::ITEMS_PER_PAGE, $page);
    }

    /**
     * Webservice for parsing address strings into parts
     */
    public function parse(array $params): View
    {
        if (!empty($_GET['address'])) {
            $parser = $this->di->get('Domain\Addresses\UseCases\Parse\Parse');
            return new Views\ParseView($parser($_GET['address']));
        }
        return new Views\ParseView();
    }

    /**
     * Display information about a single address
     */
    public function view(array $params): View
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
     * Display the address change log
     */
    public function changeLog(array $params): View
    {
        $log       = $this->di->get('Domain\Addresses\UseCases\ChangeLog\ChangeLog');
		$page      = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
        $changeLog = $log(new ChangeLogRequest(null, self::ITEMS_PER_PAGE, $page));

        return new Views\ChangeLogView($changeLog, self::ITEMS_PER_PAGE, $page);
    }

    /**
     * Create a new address
     */
    public function add(array $params): Views\AddView
    {
        global $DEFAULTS;
        $request = new AddRequest($_SESSION['USER']->id, $_REQUEST);
        if (!$request->city           ) { $request->city            = $DEFAULTS['city'           ]; }
        if (!$request->state          ) { $request->state           = $DEFAULTS['state'          ]; }
        if (!$request->locationType_id) { $request->locationType_id = $DEFAULTS['locationType_id']; }

        if (isset($_REQUEST['return_url'])) { $_SESSION['return_url'] = $_REQUEST['return_url']; }

        if (isset($_POST['street_id'])) {
            $add      = $this->di->get('Domain\Addresses\UseCases\Add\Add');
            $response = $add($request);

            if (!$response->errors) {
                if (isset($_SESSION['return_url'])) {
                    $return_url = new Url($_SESSION['return_url']);
                    $return_url->address_id = $response->address_id;
                    unset($_SESSION['return_url']);
                }
                else {
                    $return_url = View::generateUrl('addresses.view', ['id'=>$response->address_id]);
                }
                header("Location: $return_url");
                exit();
            }
            else {
                $_SESSION['errorMessages'] = $response->errors;
            }
        }
        else {
            if ($request->location_id) {
                // Load the current active location information into the request
                // as the default values
                $find = $this->di->get('Domain\Locations\UseCases\Find\Find');
                $fres = $find([
                    'location_id' => $request->location_id,
                    'active'      => true
                ]);
                if ($fres->errors || !$fres->locations) {
                    $_SESSION['errorMessages'] = $fres->errors;
                    return new \Application\Views\NotFoundView();
                }

                $request->locationType_id = $fres->locations[0]->type_id;
                $request->mailable        = $fres->locations[0]->mailable;
                $request->occupiable      = $fres->locations[0]->occupiable;
                $request->trash_day       = $fres->locations[0]->trash_day;
                $request->recycle_week    = $fres->locations[0]->recycle_week;
            }
        }

        return new Views\AddView(
            $request,
            $this->di->get('Domain\Addresses\Metadata'),
            $this->di->get('Domain\Locations\Metadata'),
             isset($_SESSION['return_url' ]) ? $_SESSION['return_url'] : View::generateUrl('addresses.index'),
            !empty($_REQUEST['street_id'  ]) ? parent::street  ((int)$_REQUEST['street_id'  ]) : null,
            !empty($_REQUEST['contact_id' ]) ? parent::person  ((int)$_REQUEST['contact_id' ]) : null,
            isset($fres->locations[0]) ? $fres->locations[0] : null
        );
    }

    /**
     * Correct an error in the primary attributes of an address
     */
    public function correct(array $params): View
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

    /**
     * Change the status on an address
     */
    public function changeStatus(array $params): View
    {
        $address_id = !empty($_REQUEST['id']) ? (int)$_REQUEST['id'] : null;
        if ($address_id) {
            $change  = $this->di->get('Domain\Addresses\UseCases\ChangeStatus\ChangeStatus');
            $request = new ChangeStatusRequest($address_id, $_SESSION['USER']->id, $_REQUEST);

            if (isset($_POST['status'])) {
                $response = $change($request);
                if (!$response->errors) {
                    header('Location: '.View::generateUrl('addresses.view', ['id'=>$address_id]));
                    exit();
                }
                else { $_SESSION['errorMessages'] = $response->errors; }
            }

            $info     = parent::addressInfo($address_id);
            $statuses = $change::statuses();
            $contact  = !empty($_REQUEST['contact_id']) ? parent::person((int)$_REQUEST['contact_id']) : null;
            if (!$request->status) {
                $request->status = $info->address->status;
            }

            return new Views\ChangeStatusView($request, $info, $statuses, $contact);
        }
        return new \Application\Views\NotFoundView();
    }

	/**
	 * Process a change of address
	 *
	 * For a change of address, we need to preserve the old address.
	 * We retire the old address, and create a new address at the same location
	 * The new address will probably have a different street and street number.
	 */
    public function readdress(array $params): View
    {
        global $DEFAULTS;

        $address_id = !empty($_REQUEST['id']) ? (int)$_REQUEST['id'] : null;
        $info       = parent::addressInfo($address_id);
        if ($address_id) {
            $request = new ReaddressRequest($address_id, $_SESSION['USER']->id, $_REQUEST);
            if (!$request->city ) { $request->city  = $DEFAULTS['city' ]; }
            if (!$request->state) { $request->state = $DEFAULTS['state']; }

            if (isset($_POST['id'])) {
                $readdress = $this->di->get('Domain\Addresses\UseCases\Readdress\Readdress');
                $response  = $readdress($request);
                if (!$response->errors) {
                    header('Location: '.View::generateUrl('addresses.view', ['id'=>$address_id]));
                    exit();
                }
                else { $_SESSION['errorMessages'] = $response->errors; }
            }
            else {
                // By default, the new address should have all the same values as
                // the old address.
                $request->setData((array)$info->address);
                // Get the active location for the address
                foreach ($info->locations as $location) {
                    if ($location->active) {
                        // The location fields for the request are different than the
                        // names used in a location object
                        $request->location_id     = $location->location_id;
                        $request->locationType_id = $location->type_id;
                        $request->mailable        = $location->mailable;
                        $request->occupiable      = $location->occupiable;
                        $request->trash_day       = $location->trash_day;
                        $request->recycle_week    = $location->recycle_week;
                        break;
                    }
                }
            }

            return new Views\ReaddressView(
                $request,
                $info,
                $this->di->get('Domain\Addresses\Metadata'),
                $this->di->get('Domain\Locations\Metadata'),
                !empty($_REQUEST['street_id' ]) ? parent::street((int)$_REQUEST['street_id' ]) : parent::street($info->address->street_id),
                !empty($_REQUEST['contact_id']) ? parent::person((int)$_REQUEST['contact_id']) : null
            );

        }
        return new \Application\Views\NotFoundView();
    }

    /**
     * Create a changeLog entry, declaring you have verified this address
     */
    public function verify(array $params): View
    {
        $address_id = !empty($_REQUEST['id']) ? (int)$_REQUEST['id'] : null;
        if ($address_id) {
            $request = new VerifyRequest($address_id, $_SESSION['USER']->id, $_REQUEST);

            if (isset($_POST['id'])) {
                $verify   = $this->di->get('Domain\Addresses\UseCases\Verify\Verify');
                $response = $verify($request);
                if (!$response->errors) {
                    header('Location: '.View::generateUrl('addresses.view', ['id'=>$address_id]));
                    exit();
                }
                $_SESSION['errorMessages'] = $response->errors;
            }

            $info     = parent::addressInfo($address_id);
            $contact  = !empty($_REQUEST['contact_id']) ? parent::person((int)$_REQUEST['contact_id']) : null;
            return new Views\VerifyView($request, $info, $contact);
        }
        return new \Application\Views\NotFoundView();
    }
}