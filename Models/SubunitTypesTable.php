<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Models;

use Blossom\Classes\TableGateway;

class SubunitTypesTable extends TableGateway
{
    public function __construct() { parent::__construct('subunit_types', __namespace__.'\SubunitType'); }

    public function find($fields=null, $order=['name'], $itemsPerPage=null, $currentPage=null)
    {
        return parent::find($fields, $order, $itemsPerPage, $currentPage);
    }
}
