<?php
/**
 * Display information about a single address
 *
 * @copyright 2020 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Addresses\Controllers;

use Application\Controller;
use Application\View;
use Application\Views\NotFoundView;
use Application\Addresses\Views\InfoView;

class ViewController extends Controller
{
    public function view(array $params): View
    {
        if (!empty($_GET['id'])) {
            $info = parent::addressInfo((int)$_GET['id']);
            if ($info->address) {
                return new InfoView($info);
            }
            else {
                $_SESSION['errorMessages'] = $info->errors;
            }
        }
        return new NotFoundView();
    }
}
