<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Plats\UseCases\Validate;

use Domain\Plats\Entities\Plat;

class Validate
{
    public function __invoke(Plat $plat): ValidateResponse
    {
        $errors = [];
        if (empty($plat->name) || empty($plat->plat_type) || empty($plat->township_id)) {
            $errors[] = 'missingRequiredFields';
        }
        return new ValidateResponse($plat, $errors);
    }
}
