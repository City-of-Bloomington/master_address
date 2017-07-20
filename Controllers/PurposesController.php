<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Controllers;

use Application\Models\Purpose;
use Application\Models\PurposesTable;
use Blossom\Classes\Controller;

class PurposesController extends Controller
{
    public function index(array $params)
    {
        $table = new PurposesTable();
        $list  = $table->find();

        return new \Application\Views\Generic\ListView([
            'list'     => $list,
            'plural'   => 'purposes',
            'singular' => 'purpose',
            'fields'   => array_keys(Purpose::$fieldmap)
        ]);
    }

    public function update(array $params)
    {
        if (!empty($_REQUEST['id'])) {
            try { $purpose = new Purpose($_REQUEST['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }
        else { $purpose = new Purpose(); }

        if (isset($purpose)) {
            if (isset($_POST['name'])) {
                try {
                    $purpose->handleUpdate($_POST);
                    $purpose->save();
                    header('Location: '.self::generateUrl('purposes.index'));
                    exit();
                }
                catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
            }
            return new \Application\Views\Generic\UpdateView([
                'form'    => 'locations/updatePurposeForm.inc',
                'purpose' => $purpose
            ]);
        }
        else {
            return new \Application\Views\NotFoundView();
        }
    }
}
