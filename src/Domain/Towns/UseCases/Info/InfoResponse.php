<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Towns\UseCases\Info;

use Domain\Towns\Entities\Town;

class InfoResponse
{
    public $town;
    public $errors = [];

    public function __construct(Town $town=null, ?array $errors=null)
    {
        if ($town  ) { $this->town   = $town;   }
        if ($errors) { $this->errors = $errors; }
    }
}
