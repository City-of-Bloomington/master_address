<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Places\Controllers;
use Application\Places\Views\InfoView;

use Application\Controller;
use Application\View;
use Application\Views\NotFoundView;

class ViewController extends Controller
{
    public function view(array $params): View
    {
        if (!empty($_GET['id'])) {
            $info = $this->di->get('Domain\Places\Actions\Info\Command');
            $res  = $info((int)$_GET['id']);
            return new InfoView($res);
        }
        return NotFoundView();
    }
}
