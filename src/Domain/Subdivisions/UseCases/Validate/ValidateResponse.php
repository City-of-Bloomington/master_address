<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Subdivisions\UseCases\Validate;

use Domain\Subdivisions\Entities\Subdivision;

class ValidateResponse
{
    public $subdivision;
    public $errors = [];

    public function __construct(Subdivision $subdivision, ?array $errors=null)
    {
        $this->subdivision = $subdivision;
        if ($errors) { $this->errors = $errors; }
    }
}
