<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Controllers;

use Application\Models\Direction;
use Application\TableGateways\Directions;
use Blossom\Classes\Controller;

class DirectionsController extends Controller
{
    public function index(array $params)
    {
        $table = new Directions();
        $list  = $table->find();

        return new \Application\Views\Generic\ListView([
            'list'     => $list,
            'plural'   => 'directions',
            'singular' => 'direction',
            'fields'   => array_keys(Direction::$fieldmap)
        ]);
    }

    public function update(array $params)
    {
        if (!empty($_REQUEST['id'])) {
            try { $direction = new Direction($_REQUEST['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }
        else { $direction = new Direction(); }

        if (isset($direction)) {
            if (isset($_POST['name'])) {
                try {
                    $direction->handleUpdate($_POST);
                    $direction->save();
                    header('Location: '.self::generateUrl('directions.index'));
                    exit();
                }
                catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
            }
            return new \Application\Views\Generic\UpdateView([
                'form'     => 'generic/updateNameCodeForm.inc',
                'plural'   => 'directions',
                'singular' => 'direction',
                'object'   => $direction
            ]);
        }
        else {
            return new \Application\Views\NotFoundView();
        }
    }
}
