<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\ZipCodes;

use Application\Controller as BaseController;
use Application\View;

class Controller extends BaseController
{
    public function index(): View
    {
        $list = $this->di->get('Domain\ZipCodes\UseCases\Index\Command');
        $res  = $list();

        return new Views\ListView($res);
    }
}
