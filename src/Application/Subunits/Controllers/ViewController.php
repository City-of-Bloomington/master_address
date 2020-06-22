<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Subunits\Controllers;

use Application\Subunits\Views\InfoView;

use Application\Controller;
use Application\View;

class ViewController extends Controller
{
    public function view(array $params): View
    {
        if (!empty($_GET['id'])) {
            $info = parent::subunitInfo((int)$_GET['id']);
            if ($info->subunit) {
                return new InfoView($info);
            }
            else {
                $_SESSION['errorMessages'] = $info->errors;
            }
        }
        return new \Application\Views\NotFoundView();
    }
}
