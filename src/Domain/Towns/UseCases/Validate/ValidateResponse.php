<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Towns\UseCases\Validate;

use Domain\Towns\Entities\Town;

class ValidateResponse
{
    public $town;
    public $errors = [];

    public function __construct(Town $town, ?array $errors=null)
    {
        $this->town = $town;
        if ($errors) { $this->errors = $errors; }
    }
}
