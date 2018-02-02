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
    public $changeLog    = [];
    public $designations = [];
    public $errors       = [];
}
