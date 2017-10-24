<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Controllers;

use Application\Models\ContactStatus;
use Application\TableGateways\ContactStatuses as StatusTable;
use Blossom\Classes\Controller;

class ContactStatuses extends Controller
{
    public function index(array $params)
    {
        $table = new StatusTable();
        $list  = $table->find();

        return new \Application\Views\Generic\ListView([
            'list'     => $list,
            'plural'   => 'contactStatuses',
            'singular' => 'contactStatus',
            'fields'   => array_keys(ContactStatus::$fieldmap)
        ]);
    }

    public function update(array $params)
    {
        if (!empty($_REQUEST['id'])) {
            try { $status = new ContactStatus($_REQUEST['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }
        else { $status = new ContactStatus(); }

        if (isset($status)) {
            if (isset($_POST['name'])) {
                try {
                    $status->handleUpdate($_POST);
                    $status->save();
                    header('Location: '.self::generateUrl('contactStatuses.index'));
                    exit();
                }
                catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
            }
            return new \Application\Views\Generic\UpdateView([
                'form'     => 'generic/updateNameForm.inc',
                'plural'   => 'contactStatuses',
                'singular' => 'contactStatus',
                'object'   => $status
            ]);
        }
        else {
            return new \Application\Views\NotFoundView();
        }
    }
}
