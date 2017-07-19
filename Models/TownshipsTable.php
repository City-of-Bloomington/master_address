<?php
/**
 * @copyright 2019-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Models;

use Blossom\Classes\TableGateway;

class TownshipsTable extends TableGateway
{
    public function __construct() { parent::__construct('townships', __namespace__.'\Township'); }
}
