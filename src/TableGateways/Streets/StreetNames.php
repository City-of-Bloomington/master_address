<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\TableGateways\Streets;

use Blossom\Classes\TableGateway;

class StreetNames extends TableGateway
{
    public function __construct() { parent::__construct('street_street_names', 'Application\Models\Streets\StreetName'); }

    public function find(array $fields=null, array $order=null, int $itemsPerPage=null, int $currentPage=null)
    {
        return parent::find($fields, $order, $itemsPerPage, $currentPage);
    }
}
