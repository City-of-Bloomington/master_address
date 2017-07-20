<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Controllers;

use Application\Models\AddressStatus;
use Application\Models\AddressStatusesTable;
use Blossom\Classes\Controller;

class AddressStatusesController extends Controller
{
    public function index(array $params)
    {
        $table = new AddressStatusesTable();
        $list  = $table->find();

        return new \Application\Views\Generic\ListView([
            'list'     => $list,
            'plural'   => 'addressStatuses',
            'singular' => 'addressStatus',
            'fields'   => array_keys(AddressStatus::$fieldmap)
        ]);
    }

    public function update(array $params)
    {
        if (!empty($_REQUEST['id'])) {
            try { $status = new AddressStatus($_REQUEST['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }
        else { $status = new AddressStatus(); }

        if (isset($status)) {
            if (isset($_POST['name'])) {
                try {
                    $status->handleUpdate($_POST);
                    $status->save();
                    header('Location: '.self::generateUrl('addressStatuses.index'));
                    exit();
                }
                catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
            }
            return new \Application\Views\Generic\UpdateView([
                'form'     => 'generic/updateNameForm.inc',
                'plural'   => 'addressStatuses',
                'singular' => 'addressStatus',
                'object'   => $status
            ]);
        }
        else {
            return new \Application\Views\NotFoundView();
        }
    }
}
