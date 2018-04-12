<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Locations\UseCases\Validate;

use Domain\Locations\Entities\Location;

class Validate
{
    public function __invoke(Location $location): ValidateResponse
    {
        $errors = [];

		// Check for required fields
		if (!$location->type_id) { $errors[] = 'missingRequiredFields'; }

        return new ValidateResponse($location, $errors);
    }
}
