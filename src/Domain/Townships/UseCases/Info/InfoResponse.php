<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Townships\UseCases\Info;

use Domain\Townships\Entities\Township;

class InfoResponse
{
    public $township;
    public $errors = [];

    public function __construct(Township $township=null, ?array $errors=null)
    {
        if ($township) { $this->township = $township; }
        if ($errors  ) { $this->errors   = $errors;   }
    }
}
