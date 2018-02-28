<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Subunits\UseCases\Validate;

use Domain\Subunits\Entities\Subunit;

class ValidateResponse
{
    public $subunit;
    public $errors = [];

    public function __construct(Subunit $subunit, ?array $errors=null)
    {
        $this->subunit = $subunit;
        $this->errors  = $errors;
    }
}
