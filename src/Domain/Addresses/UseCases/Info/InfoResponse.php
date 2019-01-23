<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\Info;

class InfoResponse
{
    public $address;
    public $locations = [];
    public $purposes  = [];
    public $subunits  = [];
    public $changeLog = [];
    public $statusLog = [];
    public $errors    = [];
}
