<?php
/**
 * @copyright 2018-2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Jurisdictions\UseCases\Validate;

use Domain\Jurisdictions\Entities\Jurisdiction;

class ValidateResponse
{
    public $jurisdiction;
    public $errors;

    public function __construct(Jurisdiction $jurisdiction, ?array $errors=null)
    {
        $this->jurisdiction = $jurisdiction;
        $this->errors       = $errors;
    }
}
