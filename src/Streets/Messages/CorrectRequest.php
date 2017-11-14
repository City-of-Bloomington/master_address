<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Streets\Messages;

use Application\People\Person;
use Application\Streets\Street;

class CorrectRequest
{
    public $street;
    public $user;        // Person doing the change

    public $streetInfo = [
        'town_id' => '',
        'notes'   => ''
    ];
    public $changeLog = [
        'contact_id' => '',
        'notes'      => ''
    ];

    public function __construct(Street $street, Person $user, array $post=null)
    {
        $this->street = $street;
        $this->user   = $user;

        if ($post) {
            $this->street['town_id'   ] = !empty($post['street']['town_id'   ]) ? $post['street']['town_id'   ] : '';
            $this->street['notes'     ] = !empty($post['street']['notes'     ]) ? $post['street']['notes'     ] : '';
            $this->change['contact_id'] = !empty($post['change']['contact_id']) ? $post['change']['contact_id'] : '';
            $this->change['notes'     ] = !empty($post['change']['notes'     ]) ? $post['change']['notes'     ] : '';
        }
        else {
            $this->streetInfo = [
                'town_id' => $street->getTown_id(),
                'notes'   => $street->getNotes()
            ];
        }
    }
}
