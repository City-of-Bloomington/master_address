<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\TableGateways\Subunits;

use Blossom\Classes\TableGateway;

class ChangeLog extends TableGateway
{
    public function __construct() { parent::__construct('subunit_change_log', 'Application\Models\Subunits\Change'); }

    public function find(array $fields=null, array $order=['action_date desc'], int $itemsPerPage=null, int $currentPage=null)
    {
        return parent::find($fields, $order, $itemsPerPage, $currentPage);
    }
}
