<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Subunits;

use Application\Controller as BaseController;
use Application\View;

class Controller extends BaseController
{
    public function view(array $params)
    {
        if (!empty($_GET['id'])) {
            $info = $this->subunitInfo((int)$_GET['id']);
            if ($info->subunit) {
                return new Views\InfoView($info);
            }
            else {
                $_SESSION['errorMessages'] = $info->errors;
            }
        }
        return new \Application\Views\NotFoundView();
    }

    private function subunitInfo(int $subunit_id): \Domain\Subunits\UseCases\Info\InfoResponse
    {
        $info = $this->di->get('Domain\Subunits\UseCases\Info\Info');
        $req  = new \Domain\Subunits\UseCases\Info\InfoRequest($subunit_id);
        return $info($req);
    }
}
