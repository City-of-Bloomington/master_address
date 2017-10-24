<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\TableGateways\Addresses;

use Application\Models\Addresses\Address;
use Blossom\Classes\TableGateway;

class Addresses extends TableGateway
{
    public function __construct()
    {
        $this->columns = Address::$fields;
        parent::__construct('addresses', 'Application\Models\Addresses\Address');
    }
}
