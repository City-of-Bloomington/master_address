<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Controllers;

use Application\Models\LocationType;
use Application\TableGateways\LocationTypes;
use Blossom\Classes\Controller;

class LocationTypesController extends Controller
{
    public function index(array $params)
    {
        $table = new LocationTypes();
        $list  = $table->find();

        return new \Application\Views\Generic\ListView([
            'list'     => $list,
            'plural'   => 'locationTypes',
            'singular' => 'locationType',
            'fields'   => array_keys(LocationType::$fieldmap)
        ]);
    }

    public function update(array $params)
    {
        if (!empty($_REQUEST['id'])) {
            try { $type = new LocationType($_REQUEST['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }
        else { $type = new LocationType(); }

        if (isset($type)) {
            if (isset($_POST['name'])) {
                try {
                    $type->handleUpdate($_POST);
                    $type->save();
                    header('Location: '.self::generateUrl('locationTypes.index'));
                    exit();
                }
                catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
            }
            return new \Application\Views\Generic\UpdateView([
                'form' => 'locations/updateTypeForm.inc',
                'type' => $type
            ]);
        }
        else {
            return new \Application\Views\NotFoundView();
        }
    }
}
