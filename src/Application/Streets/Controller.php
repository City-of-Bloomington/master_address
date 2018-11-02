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
use Domain\Addresses\UseCases\Renumber\AddressNumber;
use Domain\Addresses\UseCases\Renumber\RenumberRequest;

use Domain\Streets\Entities\Street;
use Domain\Streets\UseCases\Add\AddRequest;
use Domain\Streets\UseCases\Alias\AliasRequest;
use Domain\Streets\UseCases\ChangeStatus\ChangeStatusRequest;
use Domain\Streets\UseCases\Update\UpdateRequest;
use Domain\Streets\UseCases\Search\SearchRequest;
use Domain\Streets\UseCases\Search\SearchResponse;

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

    /**
     * Search screen for streets
     */
    public function index(array $params): View
    {
		$page   =  !empty($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = $this->di->get('Domain\Streets\UseCases\Search\Search');
        $parser = $this->di->get('Domain\Addresses\UseCases\Parse\Parse');

        $query  = !empty($_GET['street'])
                ? self::extractStreetFields($parser($_GET['street']))
                : [];
        if (!empty($_GET['town_id'])) { $query['town_id'] = (int)$_GET['town_id']; }
        if (!empty($_GET['status' ])) { $query['status' ] =      $_GET['status' ]; }

        $res    = $query
                ? $search(new SearchRequest($query, null, self::ITEMS_PER_PAGE, $page))
                : new SearchResponse();

        return new Views\SearchView($res,
                                    $this->di->get('Domain\Streets\Metadata'),
                                    self::ITEMS_PER_PAGE,
                                    $page);
    }

    /**
     * View information about a single street
     */
    public function view(array $params): View
    {
        if (!empty($_REQUEST['id'])) {
            $info = parent::streetInfo((int)$_REQUEST['id']);

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
     * Add a new street
     */
    public function add(array $params): View
    {
        $request = new AddRequest($_SESSION['USER']->id, self::readStartDate(), $_REQUEST);

        if (isset($_POST['status']) && !isset($_SESSION['errorMessages'])) {
            $add      = $this->di->get('Domain\Streets\UseCases\Add\Add');
            $response = $add($request);
            if (!$response->errors) {
                header('Location: '.View::generateUrl('streets.view', ['id'=>$response->street_id]));
                exit();
            }
            else { $_SESSION['errorMessages'] = $response->errors; }
        }

        $metadata = $this->di->get('Domain\Streets\Metadata');
        if (!$request->type_id) { $request->type_id = $metadata::TYPE_STREET; }

        $name    = !empty($_REQUEST[   'name_id']) ? parent::name  ((int)$_REQUEST[   'name_id']) : null;
        $contact = !empty($_REQUEST['contact_id']) ? parent::person((int)$_REQUEST['contact_id']) : null;
        return new Views\AddView($request, $metadata, $name, $contact);
    }

    private static function readStartDate(): \DateTime
    {
        if (!empty($_REQUEST['start_date'])) {
            try { $start_date = new \DateTime($_REQUEST['start_date']); }
            catch (\Exception $e) { $_SESSION['errorMessages'] = ['invalidDate']; }
        }
        if (!isset($start_date)) { $start_date = new \DateTime(); }

        return $start_date;
    }

    /**
     * Update an error in the primary attributes of a street
     */
    public function update(array $params): View
    {
        $street_id = (int)$_REQUEST['id'];
        if ($street_id) {
            if (isset($_POST['id'])) {
                $request  = new UpdateRequest($street_id, $_SESSION['USER']->id, $_POST);
                $update   = $this->di->get('Domain\Streets\UseCases\Update\Update');
                $response = $update($request);
                if (!count($response->errors)) {
                    header('Location: '.View::generateUrl('streets.view', ['id'=>$request->street_id]));
                    exit();
                }
                else { $_SESSION['errorMessages'] = $response->errors; }
            }
            $info    = parent::streetInfo($street_id);
            $contact = !empty($_GET['contact_id']) ? parent::person((int)$_GET['contact_id']) : null;
            if (!isset($request)) {
                $request = new UpdateRequest($street_id, $_SESSION['USER']->id, [
                    'town_id'      => $info->street->town_id,
                    'notes'        => $info->street->notes,
                    'contact_id'   => $contact ? $contact->id : null
                ]);
            }

            return new Views\UpdateView(
                $request,
                $info,
                $this->di->get('Domain\Streets\Metadata'),
                $this->addressSearch($street_id),
                $contact
            );
        }
        return new \Application\Views\NotFoundView();
    }


    /**
     * Add a new street designation
     */
    public function alias(array $params): View
    {
        $street_id = !empty($_REQUEST['id']) ? (int)$_REQUEST['id'] : null;

        if ($street_id) {
            $request   = new AliasRequest($street_id, $_SESSION['USER']->id, self::readStartDate(), $_REQUEST);

            if (isset($_POST['id'])) {
                $alias    = $this->di->get('Domain\Streets\UseCases\Alias\Alias');
                $response = $alias($request);
                if (!count($response->errors)) {
                    header('Location: '.View::generateUrl('streets.view', ['id'=>$street_id]));
                    exit();
                }
                else { $_SESSION['errorMessages'] = $response->errors; }
            }

            $info    = parent::streetInfo($street_id);
            $name    = !empty($_GET['name_id'   ]) ? $this->name  ((int)$_GET['name_id'   ]) : null;
            $contact = !empty($_GET['contact_id']) ? parent::person((int)$_GET['contact_id']) : null;

            return new Views\AliasView(
                $request,
                $info,
                $this->di->get('Domain\Streets\Metadata'),
                $name,
                $contact
            );
        }
        return new \Application\Views\NotFoundView();
    }

    /**
     * Change a street's status
     */
    public function changeStatus(array $params): View
    {
        $street_id = !empty($_REQUEST['id']) ? (int)$_REQUEST['id'] : null;
        if ($street_id) {
            $request = new ChangeStatusRequest($street_id, $_SESSION['USER']->id, $_REQUEST);

            if (isset($_POST['id'])) {
                $changeStatus = $this->di->get('Domain\Streets\UseCases\ChangeStatus\ChangeStatus');
                $response     = $changeStatus($request);
                if (!$response->errors) {
                    header('Location: '.View::generateUrl('streets.view', ['id'=>$street_id]));
                    exit();
                }
                else { $_SESSION['errorMessages'] = $response->errors; }
            }

            return new Views\ChangeStatusView(
                $request,
                parent::streetInfo  ($street_id),
                $this->di->get('Domain\Streets\Metadata'),
                $this->addressSearch($street_id),
                !empty($_GET['contact_id']) ? parent::person((int)$_GET['contact_id']) : null
            );
        }
        return new \Application\Views\NotFoundView();
    }

    /**
     * Change the street numbers for all current addresses on a street
     */
    public function renumber(array $params): View
    {
        $street_id = !empty($_REQUEST['id']) ? (int)$_REQUEST['id'] : null;
        if ($street_id) {

            if (isset(   $_POST['addresses'])) {
                $address_numbers = [];
                foreach ($_POST['addresses'] as $address_id=>$a) {
                    $address_numbers[] = new AddressNumber([
                        'address_id'           => $address_id,
                        'street_number_prefix' => $a['street_number_prefix'],
                        'street_number'        => $a['street_number'       ],
                        'street_number_suffix' => $a['street_number_suffix']
                    ]);
                }

                $request  = new RenumberRequest(
                    $address_numbers,
                    $_SESSION['USER']->id,
                    $_REQUEST
                );
                $renumber = $this->di->get('Domain\Addresses\UseCases\Renumber\Renumber');
                $response = $renumber($request);
                if (!$response->errors) {
                    header('Location: '.View::generateUrl('streets.view', ['id'=>$street_id]));
                    exit();
                }
                else { $_SESSION['errorMessages'] = $response->errors; }
            }

            if (!isset($request)) {
                $search = $this->di->get('Domain\Addresses\UseCases\Search\Search');
                $r = $search(new \Domain\Addresses\UseCases\Search\SearchRequest([
                    'street_id' => $street_id,
                    'status'    => \Domain\Logs\Metadata::STATUS_CURRENT
                ]));
                if ($r->addresses) {
                    $address_numbers = [];
                    foreach ($r->addresses as $a) {
                        $address_numbers[] = new AddressNumber([
                            'address_id'           => $a->id,
                            'street_number_prefix' => $a->street_number_prefix,
                            'street_number'        => $a->street_number,
                            'street_number_suffix' => $a->street_number_suffix
                        ]);
                    }
                    $request  = new RenumberRequest(
                        $address_numbers,
                        $_SESSION['USER']->id,
                        $_REQUEST
                    );
                }
                else {
                    header('Location: '.View::generateUrl('streets.view', ['id'=>$street_id]));
                    exit();
                }
            }

            return new Views\RenumberView(
                $request,
                parent::streetInfo($street_id),
                $this->addressSearch($street_id),
                !empty($_REQUEST['contact_id']) ? parent::person((int)$_REQUEST['contact_id']) : null
            );

        }
        return new \Application\Views\NotFoundView();
    }

    /**
     * Look up all the addresses for a street
     */
    private function addressSearch(int $street_id): \Domain\Addresses\UseCases\Search\SearchResponse
    {
        $search = $this->di->get('Domain\Addresses\UseCases\Search\Search');
        return $search(new \Domain\Addresses\UseCases\Search\SearchRequest(['street_id' => $street_id]));
    }
}
