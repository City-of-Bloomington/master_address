<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Controllers;

use Application\Models\StreetType;
use Application\Models\TableGateways\StreetTypesTable;
use Blossom\Classes\Controller;

class StreetTypesController extends Controller
{
    public function index(array $params)
    {
        $table = new StreetTypesTable();
        $list  = $table->find();

        return new \Application\Views\Generic\ListView([
            'list'     => $list,
            'plural'   => 'streetTypes',
            'singular' => 'streetType',
            'fields'   => array_keys(StreetType::$fieldmap)
        ]);
    }

    public function update(array $params)
    {
        if (!empty($_REQUEST['id'])) {
            try { $type = new StreetType($_REQUEST['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }
        else { $type = new StreetType(); }

        if (isset($type)) {
            if (isset($_POST['name'])) {
                try {
                    $type->handleUpdate($_POST);
                    $type->save();
                    header('Location: '.self::generateUrl('streetTypes.index'));
                    exit();
                }
                catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
            }
            return new \Application\Views\Generic\UpdateView([
                'form'     => 'generic/updateNameCodeForm.inc',
                'plural'   => 'streetTypes',
                'singular' => 'streetType',
                'object'   => $type
            ]);
        }
        else {
            return new \Application\Views\NotFoundView();
        }
    }
}
