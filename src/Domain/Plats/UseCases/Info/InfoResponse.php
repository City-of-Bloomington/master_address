<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Plats\UseCases\Info;

use Domain\Plats\Entities\Plat;

class InfoResponse
{
    public $plat;
    public $errors = [];

    public function __construct(?Plat $plat=null, ?array $errors=null)
    {
        if ($plat  ) { $this->plat   = $plat; }
        if ($errors) { $this->errors = $errors; }
    }
}
