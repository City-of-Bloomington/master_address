<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Streets;

use Application\Addresses\Parser;

class NamesController
{
    public function index(array $params)
    {
        $vars = ['names' => null];

        if (!empty($_GET['street'])) {
            $parse = Parser::parse($_GET['street'], 'street');
            $table = new NamesTable();
            $vars['names'] = $table->search($parse);
        }
        return new Views\Names\SearchView($vars);
    }

    public function update(array $params)
    {
    }
}
