<?php
/**
 * Check whether an address string is valid or not
 *
 * @copyright 2020 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Addresses\Controllers;

use Application\Controller;
use Application\View;
use Application\Addresses\Views\ValidateView;

class ValidateController extends Controller
{
    public function validate(array $params): View
    {
        if (!empty($_GET['query'])) {
            $validate = $this->di->get('Domain\Addresses\UseCases\Validate\Command');
            $response = $validate($_GET['query']);
            if ($response->errors) {
                $_SESSION['errorMessages'] = $response->errors;
            }
            return new ValidateView($_GET['query'], $response);
        }
        return new ValidateView();
    }
}
