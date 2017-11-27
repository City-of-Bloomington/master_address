<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Streets\Messages;

use Application\People\Person;
use Application\Streets\Street;

class StatusChangeRequest
{
    public $street;
    public $user;        // Person doing the change
    public $status;
    public $notes;

    public function __construct(Street $street, Person $user, array $post=null)
    {
        $this->street = $street;
        $this->user   = $user;

        if ($post) {
            $this->status = !empty($post['status']) ? $post['status'] : null;
            $this->notes  = !empty($post['notes' ]) ? $post['notes' ] : null;
        }
        else {
            $this->status = $street->getStatus();
        }
    }
}
