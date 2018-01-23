<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Towns\UseCases\Validate;

use Domain\Towns\Entities\Town;

class Validate
{
    public function __invoke(Town $town): ValidateResponse
    {
        $errors = [];
        if (empty($town->name) || empty($town->code)) {
            $errors[] = 'missingRequiredFields';
        }
        return new ValidateResponse($town, $errors);
    }
}
