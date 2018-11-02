<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\Designations\UseCases\Load;

use Domain\Streets\Entities\Designation;

class LoadResponse
{
    public $designation;
    public $errors = [];

    public function __construct(?Designation $designation=null, ?array $errors=null)
    {
        $this->designation = $designation;
        $this->errors      = $errors;
    }
}
