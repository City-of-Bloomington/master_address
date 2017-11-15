<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Addresses\Messages;

use Application\People\Person;
use Application\Addresses\Address;

class VerifyRequest
{
    public $address;
    public $user;        // Person doing the change
    public $notes;


    public function __construct(Address $address, Person $user, string $notes=null)
    {
        $this->address = $address;
        $this->user    = $user;
        $this->notes   = $notes;
    }
}
