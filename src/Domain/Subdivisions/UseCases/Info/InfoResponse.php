<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Subdivisions\UseCases\Info;

use Domain\Subdivisions\Entities\Subdivision;

class InfoResponse
{
    public $subdivision;
    public $errors = [];

    public function __construct(?Subdivision $subdivision=null, ?array $errors=null)
    {
        if ($subdivision) { $this->subdivision = $subdivision; }
        if ($errors     ) { $this->errors      = $errors;      }
    }
}
