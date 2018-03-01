<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\Names\UseCases\Info;
use Domain\Streets\Entities\Name;

class InfoResponse
{
    public $name;
    public $designations = [];
    public $errors       = [];
}
