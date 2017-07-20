<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Controllers;

use Application\Models\StreetStatus;
use Application\Models\StreetStatusesTable;
use Blossom\Classes\Controller;

class StreetStatusesController extends Controller
{
    public function index(array $params)
    {
        $table = new StreetStatusesTable();
        $list  = $table->find();

        return new \Application\Views\Generic\ListView([
            'list'     => $list,
            'plural'   => 'streetStatuses',
            'singular' => 'streetStatus',
            'fields'   => array_keys(StreetStatus::$fieldmap)
        ]);
    }

    public function update(array $params)
    {
        if (!empty($_REQUEST['id'])) {
            try { $status = new StreetStatus($_REQUEST['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }
        else { $status = new StreetStatus(); }

        if (isset($status)) {
            if (isset($_POST['name'])) {
                try {
                    $status->handleUpdate($_POST);
                    $status->save();
                    header('Location: '.self::generateUrl('streetStatuses.index'));
                    exit();
                }
                catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
            }
            return new \Application\Views\Generic\UpdateView([
                'form'     => 'generic/updateNameForm.inc',
                'plural'   => 'streetStatuses',
                'singular' => 'streetStatus',
                'object'   => $status
            ]);
        }
        else {
            return new \Application\Views\NotFoundView();
        }
    }
}
