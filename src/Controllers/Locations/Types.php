<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Controllers\Locations;

use Application\Models\Locations\Type;
use Application\TableGateways\Locations\Types as TypesTable;
use Blossom\Classes\View;

class Types
{
    public function index(array $params)
    {
        $table = new TypesTable();
        $list  = $table->find();

        return new \Application\Views\Generic\ListView([
            'list'     => $list,
            'plural'   => 'locationTypes',
            'singular' => 'locationType',
            'fields'   => array_keys(Type::$fieldmap)
        ]);
    }

    public function update(array $params)
    {
        if (!empty($_REQUEST['id'])) {
            try { $type = new Type($_REQUEST['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }
        else { $type = new Type(); }

        if (isset($type)) {
            if (isset($_POST['name'])) {
                try {
                    $type->handleUpdate($_POST);
                    $type->save();
                    header('Location: '.View::generateUrl('locationTypes.index'));
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
