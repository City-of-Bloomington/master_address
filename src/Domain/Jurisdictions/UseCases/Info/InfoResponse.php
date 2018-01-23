<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Jurisdictions\UseCases\Info;

use Domain\Jurisdictions\Entities\Jurisdiction;

class InfoResponse
{
    public $jurisdiction;
    public $errors = [];

    public function __construct(Jurisdiction $jurisdiction=null, ?array $errors=null)
    {
        if ($jurisdiction  ) { $this->jurisdiction   = $jurisdiction;   }
        if ($errors) { $this->errors = $errors; }
    }
}
