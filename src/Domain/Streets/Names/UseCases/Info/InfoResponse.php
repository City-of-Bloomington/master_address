<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\Names\UseCases\Info;
use Domain\Streets\Names\Entities\Name;

class InfoResponse
{
    public $name;
    public $errors = [];
    
    public function __construct(Name $name=null, ?array $errors=null)
    {
        $this->name  = $name;
        $this->erros = $errors;
    }
}
