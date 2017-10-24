<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Controllers;

use Application\Models\Plat;
use Application\TableGateways\Plats as PlatsTable;
use Blossom\Classes\Controller;

class Plats extends Controller
{
    public function index(array $params)
    {
        $table = new PlatsTable();

        if (isset($_GET['page']) && $_GET['page'] == 'all') {
            $list = $table->search($_GET);
        }
        else {
            $page  = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
            $list  = $table->search($_GET, null, 20, $page);
        }

        return new \Application\Views\Plats\SearchView(['plats'=>$list]);
    }

    public function view(array $params)
    {
        if (!empty($_GET['id'])) {
            try { $plat = new Plat($_GET['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }

        return isset($plat)
            ? new \Application\Views\Plats\InfoView(['plat'=>$plat])
            : new \Application\Views\NotFoundView();
    }

    public function update(array $params)
    {
        if (!empty($_REQUEST['id'])) {
            try { $plat = new Plat($_REQUEST['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }
        else { $plat = new Plat(); }

        if (isset($plat)) {
            if (isset($_POST['name'])) {
                try {
                    $plat->handleUpdate($_POST);
                    $plat->save();
                    header('Location: '.parent::generateUrl('plats.view', ['id'=>$plat->getId()]));
                    exit();
                }
                catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
            }

            return new \Application\Views\Generic\UpdateView([
                'form'=>'plats/updateForm.inc',
                'plat'=>$plat
            ]);
        }

        return new Application\Views\NotFoundView();
    }
}
