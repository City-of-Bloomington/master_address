<?php
/**
 * @copyright 2018-2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Subdivisions\UseCases\Info;

use Domain\Subdivisions\Entities\Subdivision;

class InfoResponse
{
    public $subdivision;
    public $errors;

    public function __construct(?Subdivision $subdivision=null, ?array $errors=null)
    {
        $this->subdivision = $subdivision;
        $this->errors      = $errors;
    }
}
