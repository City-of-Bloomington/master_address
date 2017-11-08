<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Controllers\Subunits;

use Application\Models\Subunits\Subunit;

class Subunits
{
    public function view(array $params)
    {
        if (!empty($_GET['id'])) {
            try { $subunit = new Subunit($_GET['id']); }
            catch (\Exception $e) { $_SESSION['errorMessages'][] = $e; }
        }

        if (isset($subunit)) {
            return new \Application\Views\Subunits\InfoView(['subunit'=>$subunit]);
        }
        return new \Application\Views\NotFoundView();
    }
}
