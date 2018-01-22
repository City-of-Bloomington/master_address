<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Towns;

use Blossom\Classes\Database;
use Application\View;

use Domain\Towns\UseCases\Search\Search;
use Domain\Towns\UseCases\Search\SearchRequest;

class Controller
{
    private $di;

    public function __construct()
    {
        global $DI;
        $this->di = $DI;
    }

    public function index(array $params)
    {
        $search = $this->di->get('Domain\Towns\UseCases\Search\Search');
        $user   = isset($_SESSION['USER']) ? $_SESSION['USER'] : null;
        $req    = new SearchRequest($user);
        $res    = $search($req);

        return new Views\ListView($res);
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
                    header('Location: '.View::generateUrl('towns.index'));
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
