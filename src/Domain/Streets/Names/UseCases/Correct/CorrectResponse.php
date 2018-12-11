<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\Names\UseCases\Correct;

class CorrectResponse
{
    public $name_id;
    public $errors = [];

    public function __construct(?int $name_id=null, ?array $errors=null)
    {
        $this->name_id = $name_id;
        $this->errors  = $errors;
    }
}
