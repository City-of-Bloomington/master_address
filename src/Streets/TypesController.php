<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Streets;

use Blossom\Classes\View;

class TypesController
{
    public function index(array $params)
    {
        $table = new TypesTable();
        $list  = $table->find();

        return new Views\Types\ListView(['types'=>$list]);
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
                    header('Location: '.View::generateUrl('streetTypes.index'));
                    exit();
                }
                catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
            }
            return new Views\Types\UpdateView(['type'=>$type]);
        }
        else {
            return new \Application\Views\NotFoundView();
        }
    }
}
