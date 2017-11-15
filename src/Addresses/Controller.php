<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Addresses;

use Blossom\Classes\View;

class Controller
{
    public function index(array $params)
    {
        $vars['addresses'] = null;

        if (!empty($_GET['address'])) {
            $parse = Parser::parse($_GET['address']);
            $table = new AddressesTable();

            if (isset($_GET['page']) && $_GET['page'] == 'all') {
                $vars['addresses'] = $table->find($parse);
            }
            else {
                $page  = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
                $vars['addresses'] = $table->find($parse, null, 20, $page);
            }
        }
        return new Views\SearchView($vars);
    }

    public function parse(array $params)
    {
    }

    public function view(array $params)
    {
        if (!empty($_GET['id'])) {
            try { $address = new Address($_GET['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }

        if (isset($address)) {
            return new Views\InfoView(['address'=>$address]);
        }
        return new \Application\Views\NotFoundView();
    }

    public function verify(array $params)
    {
        if (!empty($_REQUEST['id'])) {
            try { $address = new Address($_REQUEST['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }

        if (isset($address)) {
            if (isset($_POST['id'])) {
                $verification = new Messages\VerifyRequest($address, $_SESSION['USER'], $_POST['notes']);
                try {
                    $address->verify($verification);
                    header('Location: '.View::generateUrl('addresses.view', ['id'=>$address->getId()]));
                    exit();
                }
                catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
            }
            else {
                $verification = new Messages\VerifyRequest($address, $_SESSION['USER']);
            }
            return new Views\VerifyView(['request'=>$verification]);
        }
        return new \Application\Views\NotFoundView();
    }
}
