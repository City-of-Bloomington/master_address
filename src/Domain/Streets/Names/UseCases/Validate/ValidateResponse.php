<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\Names\UseCases\Validate;

use Domain\Streets\Entities\Name;

class ValidateResponse
{
    public $name;
    public $errors;

    public function __construct(?Name $name=null, ?array $errors=null)
    {
        if ($name  ) { $this->name   = $name;   }
        if ($errors) { $this->errors = $errors; }
    }
}
