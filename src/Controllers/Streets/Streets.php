<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Controllers\Streets;

use Application\Models\Addresses\Parser;
use Application\Models\Streets\Street;
use Application\TableGateways\Streets\Streets as StreetsTable;
use Blossom\Classes\View;

class Streets
{
    public function index(array $params)
    {
        $vars = ['streets' => null];

        if (!empty($_GET['street'])) {
            $parse = Parser::parse($_GET['street'], 'street');
            $table = new StreetsTable();
            $vars['streets'] = $table->search($parse);
        }
        return new \Application\Views\Streets\SearchView($vars);
    }

    public function view(array $params)
    {
        if (!empty($_GET['id'])) {
            try { $street = new Street($_GET['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }

        if (isset($street)) {
            return new \Application\Views\Streets\InfoView(['street'=>$street]);
        }
        return new \Application\Views\NotFoundView();
    }
}
