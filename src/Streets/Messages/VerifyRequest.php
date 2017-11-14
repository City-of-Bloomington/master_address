<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Streets\Messages;

use Application\People\Person;
use Application\Streets\Street;

class VerifyRequest
{
    public $street;
    public $user;        // Person doing the change
    public $notes;


    public function __construct(Street $street, Person $user, string $notes=null)
    {
        $this->street = $street;
        $this->user   = $user;
        $this->notes  = $notes;
    }
}
