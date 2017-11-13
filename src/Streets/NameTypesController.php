<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Streets;

use Blossom\Classes\View;

class NameTypesController
{
    public function index(array $params)
    {
        $table = new NameTypesTable();
        $list  = $table->find();

        return new Views\NameTypes\ListView(['types'=>$list]);
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
                    header('Location: '.View::generateUrl('streetNameTypes.index'));
                    exit();
                }
                catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
            }
            return new Views\NameTypes\UpdateView(['type'=>$type]);
        }
        else {
            return new \Application\Views\NotFoundView();
        }
    }
}
