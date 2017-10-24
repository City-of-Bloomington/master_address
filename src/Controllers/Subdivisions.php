<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Controllers;

use Application\Models\Subdivision;
use Application\TableGateways\Subdivisions as SubdivisionsTable;
use Blossom\Classes\Controller;

class Subdivisions extends Controller
{
    public function index(array $params)
    {
        $table = new SubdivisionsTable();

        if (isset($_GET['page']) && $_GET['page'] == 'all') {
            $list = $table->search($_GET);
        }
        else {
            $page  = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
            $list  = $table->search($_GET, null, 20, $page);
        }

        return new \Application\Views\Subdivisions\SearchView(['subdivisions'=>$list]);
    }

    public function view(array $params)
    {
        if (!empty($_GET['id'])) {
            try { $subdivision = new Subdivision($_GET['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }

        return isset($subdivision)
            ? new \Application\Views\Subdivisions\InfoView(['subdivision'=>$subdivision])
            : new \Application\Views\NotFoundView();
    }

    public function update(array $params)
    {
        if (!empty($_REQUEST['id'])) {
            try { $subdivision = new Subdivision($_REQUEST['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }
        else { $subdivision = new Subdivision(); }

        if (isset($subdivision)) {
            if (isset($_POST['name'])) {
                try {
                    $subdivision->handleUpdate($_POST);
                    $subdivision->save();
                    header('Location: '.parent::generateUrl('subdivisions.view', ['id'=>$subdivision->getId()]));
                    exit();
                }
                catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
            }

            return new \Application\Views\Generic\UpdateView([
                'form'=>'subdivisions/updateForm.inc',
                'subdivision'=>$subdivision
            ]);
        }

        return new Application\Views\NotFoundView();
    }
}
