<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Controllers;

use Application\Models\Jurisdiction;
use Application\Models\JurisdictionsTable;
use Blossom\Classes\Controller;

class JurisdictionsController extends Controller
{
    public function index(array $params)
    {
        $table = new JurisdictionsTable();
        $list  = $table->find();

        return new \Application\Views\Jurisdictions\ListView(['jurisdictions'=>$list]);
    }

    public function update(array $params)
    {
        if (!empty($_REQUEST['id'])) {
            try { $jurisdiction = new Jurisdiction($_REQUEST['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }
        else { $jurisdiction = new Jurisdiction(); }

        if (isset($jurisdiction)) {
            if (isset($_POST['name'])) {
                try {
                    $jurisdiction->handleUpdate($_POST);
                    $jurisdiction->save();
                    header('Location: '.self::generateUrl('jurisdictions.index'));
                    exit();
                }
                catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
            }
            return new \Application\Views\Jurisdictions\UpdateView(['jurisdiction'=>$jurisdiction]);
        }
        else {
            return new \Application\Views\NotFoundView();
        }
    }
}
