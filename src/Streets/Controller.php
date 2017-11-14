<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Streets;

use Application\Addresses\Parser;
use Aura\Router\Route;
use Blossom\Classes\View;

class Controller
{
    public function index(array $params)
    {
        $vars = ['streets' => null];

        if (!empty($_GET['street'])) {
            $parse = Parser::parse($_GET['street'], 'street');
            $table = new StreetsTable();
            $vars['streets'] = $table->search($parse);
        }
        return new Views\SearchView($vars);
    }

    public function view(array $params)
    {
        if (!empty($_GET['id'])) {
            try { $street = new Street($_GET['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }

        if (isset($street)) {
            return new Views\InfoView(['street'=>$street]);
        }
        return new \Application\Views\NotFoundView();
    }

    public function correct(array $params)
    {
        if (!empty($_REQUEST['id'])) {
            try { $street  = new Street($_REQUEST['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }

        if (isset($street)) {
            if (isset($_POST['id'])) {
                $correction = new Messages\CorrectRequest($street, $_SESSION['USER'], $_POST);
                try {
                    $street->correct($correction);
                    header('Location: '.View.generateUrl('streets.view', ['id'=>$street->getId()]));
                    exit();
                }
                catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
            }
            else {
                $correction = new Messages\CorrectRequest($street, $_SESSION['USER']);
            }
            return new Views\Actions\CorrectView(['request'=>$correction]);
        }
        return new \Application\Views\NotFoundView();
    }
}
