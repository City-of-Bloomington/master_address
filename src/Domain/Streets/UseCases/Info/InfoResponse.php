<?php
/**
 * @copyright 2018-2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Streets\UseCases\Info;

class InfoResponse
{
    public $street;
    public $changeLog;
    public $designations;
    public $errors;
}
