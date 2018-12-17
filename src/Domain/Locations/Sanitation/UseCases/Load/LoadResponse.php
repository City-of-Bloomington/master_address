<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Locations\Sanitation\UseCases\Load;

use Domain\Locations\Entities\Sanitation;

class LoadResponse
{
    public $sanitation;
    public $errors = [];

    public function __construct(?Sanitation $sanitation=null, ?array $errors=null)
    {
        $this->sanitation = $sanitation;
        $this->errors     = $errors;
    }
}
