<?php
/**
 * Make a change in the descriptive properties of an address
 *
 * Descriptive properties are the fields of the address not used
 * in the "correct" action, such as  street number, street name, and zip.
 *
 * This action is typically taken to fix information we have on record for
 * an address.  We do not need to report address "Updates" to outside agencies.
 *
 * @copyright 2020 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Addresses\Controllers;

use Application\Controller;
use Application\View;
use Application\Views\NotFoundView;

use Application\Addresses\Views\UpdateView;
use Domain\Addresses\UseCases\Update\Request as UpdateRequest;

class UpdateController extends Controller
{
    public function update(array $params): View
    {
        $address_id = !empty($_REQUEST['id']) ? (int)$_REQUEST['id'] : null;
        if ($address_id) {
            if (isset($_POST['id'])) {
                $request  = new UpdateRequest($address_id, $_SESSION['USER']->id, $_POST);
                $update   = $this->di->get('Domain\Addresses\UseCases\Update\Command');
                $response = $update($request);
                if (!count($response->errors)) {
                    header('Location: '.View::generateUrl('addresses.view', ['id'=>$address_id]));
                    exit();
                }
                else { $_SESSION['errorMessages'] = $response->errors; }
            }

            $info     = parent::addressInfo($address_id);
            $location = $info->activeCurrentLocation();
            $contact  = !empty($_REQUEST['contact_id']) ? parent::person((int)$_REQUEST['contact_id']) : null;
            if (!isset($request)) {
                $request  = new UpdateRequest($address_id, $_SESSION['USER']->id, [
                    'address2'        => $info->address->address2,
                    'address_type'    => $info->address->address_type,
                    'jurisdiction_id' => $info->address->jurisdiction_id,
                    'township_id'     => $info->address->township_id,
                    'subdivision_id'  => $info->address->subdivision_id,
                    'plat_id'         => $info->address->plat_id,
                    'section'         => $info->address->section,
                    'quarter_section' => $info->address->quarter_section,
                    'plat_lot_number' => $info->address->plat_lot_number,
                    'notes'           => $info->address->notes,
                    'locationType_id' => $location ? $location->type_id       : null,
                    'mailable'        => $location ? $location->mailable      : null,
                    'occupiable'      => $location ? $location->occupiable    : null,
                    'group_quarter'   => $location ? $location->group_quarter : null
                ]);
            }
            return new UpdateView($request,
                                  $this->di->get('Domain\Addresses\Metadata'),
                                  $this->di->get('Domain\Locations\Metadata'),
                                  $info,
                                  $contact);
        }
        return new NotFoundView();
    }
}
