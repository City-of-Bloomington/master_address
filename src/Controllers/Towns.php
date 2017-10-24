<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Controllers;

use Application\Models\Town;
use Application\TableGateways\Towns as TownsTable;
use Blossom\Classes\Controller;

class Towns extends Controller
{
    public function index(array $params)
    {
        $table = new TownsTable();
        $list  = $table->find();

        return new \Application\Views\Generic\ListView([
            'list'     => $list,
            'plural'   => 'towns',
            'singular' => 'town',
            'fields'   => array_keys(Town::$fieldmap)
        ]);
    }

    public function update(array $params)
    {
        if (!empty($_REQUEST['id'])) {
            try { $town = new Town($_REQUEST['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }
        else { $town = new Town(); }

        if (isset($town)) {
            if (isset($_POST['name'])) {
                try {
                    $town->handleUpdate($_POST);
                    $town->save();
                    header('Location: '.self::generateUrl('towns.index'));
                    exit();
                }
                catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
            }
            return new \Application\Views\Generic\UpdateView([
                'form'     => 'generic/updateNameCodeForm.inc',
                'plural'   => 'towns',
                'singular' => 'town',
                'object'   => $town
            ]);
        }
        else {
            return new \Application\Views\NotFoundView();
        }
    }
}
