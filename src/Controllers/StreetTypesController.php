<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Controllers;

use Application\Models\Streets\Type;
use Application\Models\TableGateways\StreetTypes;
use Blossom\Classes\Controller;

class StreetTypesController extends Controller
{
    public function index(array $params)
    {
        $table = new StreetTypes();
        $list  = $table->find();

        return new \Application\Views\Streets\Types\ListView(['types'=>$list]);
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
                    header('Location: '.self::generateUrl('streetTypes.index'));
                    exit();
                }
                catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
            }
            return new \Application\Views\Streets\Types\UpdateView(['type'=>$type]);
        }
        else {
            return new \Application\Views\NotFoundView();
        }
    }
}
