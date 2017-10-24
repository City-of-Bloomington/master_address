<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Controllers;

use Application\Models\Addresses\Status;
use Application\TableGateways\Addresses\Statuses;
use Blossom\Classes\Controller;

class AddressStatuses extends Controller
{
    public function index(array $params)
    {
        $table = new Statuses();
        $list  = $table->find();

        return new \Application\Views\Generic\ListView([
            'list'     => $list,
            'plural'   => 'addressStatuses',
            'singular' => 'addressStatus',
            'fields'   => array_keys(Status::$fieldmap)
        ]);
    }

    public function update(array $params)
    {
        if (!empty($_REQUEST['id'])) {
            try { $status = new Status($_REQUEST['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }
        else { $status = new Status(); }

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
