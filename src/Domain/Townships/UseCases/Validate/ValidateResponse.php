<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Townships\UseCases\Validate;

use Domain\Townships\Entities\Township;

class ValidateResponse
{
    public $township;
    public $errors = [];

    public function __construct(Township $township, ?array $errors=null)
    {
        $this->township = $township;
        if ($errors) { $this->errors = $errors; }
    }
}
