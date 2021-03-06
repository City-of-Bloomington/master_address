<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Places\Actions\Info;
use Domain\Places\Entities\Place;

class Response
{
    public $place;
    public $history   = [];
    public $alt_names = [];
    public $locations = [];
    public $errors    = [];
}
