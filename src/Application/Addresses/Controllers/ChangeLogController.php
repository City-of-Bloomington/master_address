<?php
/**
 * Display the address change log
 *
 * @copyright 2020 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Addresses\Controllers;

use Application\Controller;
use Application\View;
use Application\Views\NotFoundView;

use Application\Addresses\Views\ChangeLogView;
use Domain\Addresses\UseCases\ChangeLog\ChangeLogRequest;

class ChangeLogController extends Controller
{
    public function changeLog(array $params): View
    {
        $log       = $this->di->get('Domain\Addresses\UseCases\ChangeLog\ChangeLog');
		$page      = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
        $changeLog = $log(new ChangeLogRequest(null, parent::ITEMS_PER_PAGE, $page));

        return new ChangeLogView($changeLog, parent::ITEMS_PER_PAGE, $page);
    }
}
