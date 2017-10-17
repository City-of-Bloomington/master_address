<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Controllers;

use Application\Models\Jurisdiction;
use Application\TableGateways\Jurisdictions;
use Blossom\Classes\Controller;

class JurisdictionsController extends Controller
{
    public function index(array $params)
    {
        $table = new Jurisdictions();
        $list  = $table->find();

        return new \Application\Views\Generic\ListView([
            'list'     => $list,
            'plural'   => 'jurisdictions',
            'singular' => 'jurisdiction',
            'fields'   => array_keys(Jurisdiction::$fieldmap)
        ]);
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
            return new \Application\Views\Generic\UpdateView([
                'object'   => $jurisdiction,
                'plural'   => 'jurisdictions',
                'singular' => 'jurisdiction',
                'form'     => 'generic/updateNameForm.inc'
            ]);
        }
        else {
            return new \Application\Views\NotFoundView();
        }
    }
}
