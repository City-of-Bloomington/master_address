<?php
/**
 * @copyright 2017-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Plats;

use Application\Controller as BaseController;
use Application\View;

use Domain\Plats\Entities\Plat;
use Domain\Plats\UseCases\Info\InfoRequest;
use Domain\Plats\UseCases\Search\SearchRequest;
use Domain\Plats\UseCases\Update\UpdateRequest;

class Controller extends BaseController
{
    const ITEMS_PER_PAGE = 20;

    public function index(array $params)
    {
		$page   =  !empty($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = $this->di->get('Domain\Plats\UseCases\Search\Search');
        $res    = $search(new SearchRequest($_GET, null, self::ITEMS_PER_PAGE, $page));

        return new Views\SearchView($res, self::ITEMS_PER_PAGE, $page);
    }

    public function view(array $params)
    {
        if (!empty($_GET['id'])) {
            try { $plat = new Plat($_GET['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }

        return isset($plat)
            ? new Views\InfoView(['plat'=>$plat])
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
