<?php
/**
 * Process a change of address
 *
 * For a change of address, we need to preserve the old address.
 * We retire the old address, and create a new address at the same location
 * The new address will probably have a different street and street number.
 *
 * @copyright 2020 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Addresses\Controllers;

use Application\Controller;
use Application\View;
use Application\Views\NotFoundView;

use Application\Addresses\Views\ReaddressView;
use Domain\Addresses\UseCases\Readdress\ReaddressRequest;

class ReaddressController extends Controller
{
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
                        break;
                    }
                }
            }

            return new ReaddressView(
                $request,
                $info,
                $this->di->get('Domain\Addresses\Metadata'),
                $this->di->get('Domain\Locations\Metadata'),
                !empty($_REQUEST['street_id' ]) ? parent::street((int)$_REQUEST['street_id' ]) : parent::street($info->address->street_id),
                !empty($_REQUEST['contact_id']) ? parent::person((int)$_REQUEST['contact_id']) : null
            );

        }
        return new NotFoundView();
    }
}
