<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\UseCases\Validate;

use Domain\Streets\Entities\Street;

class Validate
{
    public function __invoke(Street $street): ValidateResponse
    {
        $errors = [];

        if (!$street->status) {
            $errors[] = 'missingRequiredFields';
        }

        return new ValidateResponse($street, $errors);
    }
}
