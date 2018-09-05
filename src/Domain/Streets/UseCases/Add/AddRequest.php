<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\UseCases\Add;

class AddRequest
{
    // Street fields
    public $town_id;
    public $status;
    public $notes;

    // Designation fields
    public $type_id;
    public $name_id;
    public $rank;

    // Change log entry
    public $user_id;
    public $contact_id;
    public $change_notes;

    public function __construct(int $user_id, ?array $data=null)
    {
        $this->user_id   = $user_id;

        if (!empty($data['town_id'])) { $this->town_id = (int)$data['town_id']; }
        if (!empty($data['status' ])) { $this->status  =      $data['status' ]; }
        if (!empty($data['notes'  ])) { $this->notes   =      $data['notes'  ]; }

        if (!empty($data['type_id'])) { $this->type_id = (int)$data['type_id']; }
        if (!empty($data['name_id'])) { $this->name_id = (int)$data['name_id']; }
        if (!empty($data['rank'   ])) { $this->rank    = (int)$data['rank'   ]; }


        if (!empty($data['contact_id'  ])) { $this->contact_id   = (int)$data['contact_id'  ]; }
        if (!empty($data['change_notes'])) { $this->change_notes =      $data['change_notes']; }
    }
}
