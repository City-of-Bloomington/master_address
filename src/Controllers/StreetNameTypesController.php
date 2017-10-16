<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Controllers;

use Application\Models\Streets\NameType;
use Application\Models\TableGateways\Streets\NameTypes;
use Blossom\Classes\Controller;

class StreetNameTypesController extends Controller
{
    public function index(array $params)
    {
        $table = new NameTypes();
        $list  = $table->find();

        return new \Application\Views\Streets\NameTypes\ListView(['types'=>$list]);
    }

    public function update(array $params)
    {
        if (!empty($_REQUEST['id'])) {
            try { $type = new NameType($_REQUEST['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }
        else { $type = new NameType(); }

        if (isset($type)) {
            if (isset($_POST['name'])) {
                try {
                    $type->handleUpdate($_POST);
                    $type->save();
                    header('Location: '.self::generateUrl('streetNameTypes.index'));
                    exit();
                }
                catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
            }
            return new \Application\Views\Streets\NameTypes\UpdateView(['type'=>$type]);
        }
        else {
            return new \Application\Views\NotFoundView();
        }
    }
}
