<?php
/**
 * Correct an error in the primary attributes of an address
 *
 * @copyright 2020 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Addresses\Controllers;

use Application\Controller;
use Application\View;
use Application\Views\NotFoundView;

use Application\Addresses\Views\CorrectView;
use Domain\Addresses\UseCases\Correct\CorrectRequest;

class CorrectController extends Controller
{
    public function correct(array $params): View
    {
        $address_id = !empty($_REQUEST['id']) ? (int)$_REQUEST['id'] : null;
        if ($address_id) {
            if (isset($_POST['id'])) {
                $request  = new CorrectRequest($address_id, $_SESSION['USER']->id, $_POST);
                $correct  = $this->di->get('Domain\Addresses\UseCases\Correct\Correct');
                $response = $correct($request);
                if (!$response->errors) {
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
            return new CorrectView($request, $info, $street, $contact);
        }
        return new NotFoundView();
    }
}
