<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Controllers;

use Application\Models\SubunitType;
use Application\TableGateways\SubunitTypes;
use Blossom\Classes\Controller;

class SubunitTypesController extends Controller
{
    public function index(array $params)
    {
        $table = new SubunitTypes();
        $list  = $table->find();

        return new \Application\Views\Generic\ListView([
            'list'     => $list,
            'plural'   => 'subunitTypes',
            'singular' => 'subunitType',
            'fields'   => array_keys(SubunitType::$fieldmap)
        ]);
    }

    public function update(array $params)
    {
        if (!empty($_REQUEST['id'])) {
            try { $type = new SubunitType($_REQUEST['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }
        else { $type = new SubunitType(); }

        if (isset($type)) {
            if (isset($_POST['name'])) {
                try {
                    $type->handleUpdate($_POST);
                    $type->save();
                    header('Location: '.self::generateUrl('subunitTypes.index'));
                    exit();
                }
                catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
            }
            return new \Application\Views\Generic\UpdateView([
                'form'     => 'generic/updateNameCodeForm.inc',
                'plural'   => 'subunitTypes',
                'singular' => 'subunitType',
                'object'   => $type
            ]);
        }
        else {
            return new \Application\Views\NotFoundView();
        }
    }
}
