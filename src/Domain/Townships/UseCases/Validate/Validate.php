<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Townships\UseCases\Validate;

use Domain\Townships\Entities\Township;

class Validate
{
    public function __invoke(Township $township): ValidateResponse
    {
        $errors = [];
        if (empty($township->name) || empty($township->code)) {
            $errors[] = 'missingRequiredFields';
        }
        return new ValidateResponse($township, $errors);
    }
}
