<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Subunits\UseCases\Info;

class InfoResponse
{
    public $subunit;
    public $address;
    public $locations = [];
    public $changeLog = [];
    public $statusLog = [];
    public $errors    = [];
}