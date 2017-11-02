<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Controllers\Addresses;

use Application\Models\Addresses\Parser;
use Application\TableGateways\Addresses\Addresses as AddressesTable;
use Blossom\Classes\View;

class Addresses
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
        return new \Application\Views\Addresses\SearchView($vars);
    }

    public function parse(array $params)
    {
    }

    public function view(array $params)
    {
    }

    public function move(array $params)
    {
    }
}
