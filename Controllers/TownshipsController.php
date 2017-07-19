<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Controllers;

use Application\Models\Township;
use Application\Models\TownshipsTable;
use Blossom\Classes\Controller;

class TownshipsController extends Controller
{
    public function index(array $params)
    {
        $table = new TownshipsTable();
        $list  = $table->find();

        return new \Application\Views\Townships\ListView(['townships'=>$list]);
    }

    public function update(array $params)
    {
        if (!empty($_REQUEST['id'])) {
            try { $township = new Township($_REQUEST['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }
        else { $township = new Township(); }

        if (isset($township)) {
            if (isset($_POST['name'])) {
                try {
                    $township->handleUpdate($_POST);
                    $township->save();
                    header('Location: '.self::generateUrl('townships.index'));
                    exit();
                }
                catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
            }
            return new \Application\Views\Townships\UpdateView(['township'=>$township]);
        }
        else {
            return new \Application\Views\NotFoundView();
        }
    }
}
