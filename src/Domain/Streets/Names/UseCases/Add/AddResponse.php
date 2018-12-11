<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\Names\UseCases\Add;

class AddResponse
{
    public $id;
    public $errors = [];

    public function __construct(?int $id=null, ?array $errors=null)
    {
        if ($id    ) { $this->id     = $id;     }
        if ($errors) { $this->errors = $errors; }
    }
}
