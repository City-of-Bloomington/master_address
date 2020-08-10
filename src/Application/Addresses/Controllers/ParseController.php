<?php
/**
 * Webservice for parsing address strings into parts
 *
 * @copyright 2020 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Addresses\Controllers;

use Application\Controller;
use Application\View;
use Application\Addresses\Views\ParseView;

class ParseController extends Controller
{
    public function parse(array $params): View
    {
        if (!empty($_GET['address'])) {
            $parser = $this->di->get('Domain\Addresses\UseCases\Parse\Parse');
            return new ParseView($parser($_GET['address']));
        }
        return new ParseView();
    }
}
