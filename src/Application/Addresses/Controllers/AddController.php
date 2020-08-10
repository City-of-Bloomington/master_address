<?php
/**
 * Create a new address
 *
 * @copyright 2020 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Addresses\Controllers;

use Application\Controller;
use Application\View;
use Application\Url;
use Application\Views\NotFoundView;

use Application\Addresses\Views\AddView;
use Domain\Addresses\UseCases\Add\AddRequest;
use Domain\Addresses\UseCases\Search\SearchRequest;

class AddController extends Controller
{
    public function add(array $params): View
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
                // Load the current active address location information into the request
                // as the default values.
                $find = $this->di->get('Domain\Addresses\UseCases\Search\Search');
                $fres = $find(new SearchRequest([
                    'location_id' => $request->location_id
                ]));
                if ($fres->errors || !$fres->addresses) {
                    $_SESSION['errorMessages'] = $fres->errors;
                    return new NotFoundView();
                }
                foreach ($request as $k=>$v) {
                    if (!$v && isset($fres->addresses[0]->$k)) {
                        $request->$k = $fres->addresses[0]->$k;
                    }
                }

                // This is usually a corner address, so we know the street and number
                // will be different for the new address.  We should empty out those fields
                $request->street_number_prefix = null;
                $request->street_number        = null;
                $request->street_number_suffix = null;
                $request->street_id            = null;
            }
        }

        return new AddView(
            $request,
            $this->di->get('Domain\Addresses\Metadata'),
            $this->di->get('Domain\Locations\Metadata'),
             isset($_SESSION['return_url' ]) ? $_SESSION['return_url'] : View::generateUrl('addresses.index'),
            !empty($_REQUEST['street_id'  ]) ? parent::street  ((int)$_REQUEST['street_id'  ]) : null,
            !empty($_REQUEST['contact_id' ]) ? parent::person  ((int)$_REQUEST['contact_id' ]) : null
        );
    }
}
