<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Subunits\UseCases\Validate;

use Domain\Subunits\Entities\Subunit;

class Validate
{
    const MISSING_ADDRESS = 'missingAddress';
    const MISSING_STATUS  = 'missingStatus';

    public function __invoke(Subunit $subunit): ValidateResponse
    {
        $errors = [];

		// Check for required fields
		if (!$subunit->address_id) { $errors[] = self::MISSING_ADDRESS; }
		if (!$subunit->status    ) { $errors[] = self::MISSING_STATUS;  }

        return new ValidateResponse($subunit, $errors);
    }
}
