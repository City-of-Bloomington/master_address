<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Jurisdictions\UseCases\Validate;

use Domain\Jurisdictions\Entities\Jurisdiction;

class Validate
{
    public function __invoke(Jurisdiction $jurisdiction): ValidateResponse
    {
        $errors = [];
        if (empty($jurisdiction->name)) {
            $errors[] = 'missingRequiredFields';
        }
        return new ValidateResponse($jurisdiction, $errors);
    }
}
