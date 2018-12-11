<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\Names\UseCases\Load;

use Domain\Streets\Entities\Name;

class LoadResponse
{
    public $name;
    public $errors = [];

    public function __construct(?Name $name=null, ?array $errors=null)
    {
        $this->name   = $name;
        $this->errors = $errors;
    }
}
