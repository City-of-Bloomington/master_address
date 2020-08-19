<?php
/**
 * @copyright 2018-2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\People\UseCases\Info;

use Domain\People\Entities\Person;

class InfoResponse
{
    public $person;
    public $errors;

    public function __construct(?Person $person=null, ?array $errors=null)
    {
        $this->person = $person;
        $this->errors = $errors;
    }
}
