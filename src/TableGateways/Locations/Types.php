<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\TableGateways\Locations;

use Blossom\Classes\TableGateway;

class Types extends TableGateway
{
    public function __construct() { parent::__construct('location_types', 'Application\Models\Locations\Type'); }

    public function find(array $fields=null, array $order=['name'], int $itemsPerPage=null, int $currentPage=null)
    {
        return parent::find($fields, $order, $itemsPerPage, $currentPage);
    }
}
