<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\UseCases\Info;

use Domain\Streets\Entities\Street;

class InfoResponse
{
    public $street;
    public $errors = [];

    public function __construct(?Street $street=null, ?array $errors=null)
    {
        if ($street) { $this->street = $street; }
        if ($errors) { $this->errors = $errors; }
    }
}
