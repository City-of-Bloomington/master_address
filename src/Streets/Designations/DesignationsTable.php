<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Streets\Designations;

use Blossom\Classes\TableGateway;

class DesignationsTable extends TableGateway
{
    public function __construct() { parent::__construct('street_designations', __namespace__.'\Designation'); }

    public function find(array $fields=null, array $order=null, int $itemsPerPage=null, int $currentPage=null)
    {
        return parent::find($fields, $order, $itemsPerPage, $currentPage);
    }
}
