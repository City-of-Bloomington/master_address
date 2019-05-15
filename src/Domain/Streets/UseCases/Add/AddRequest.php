<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
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
    public $name_id;
    public $start_date;

    // Change log entry
    public $user_id;
    public $contact_id;
    public $change_notes;

    public function __construct(int $user_id, \DateTime $start_date, ?array $data=null)
    {
        $this->user_id    = $user_id;
        $this->start_date = $start_date;

        if (!empty($data['town_id'])) { $this->town_id = (int)$data['town_id']; }
        if (!empty($data['status' ])) { $this->status  =      $data['status' ]; }
        if (!empty($data['notes'  ])) { $this->notes   =      $data['notes'  ]; }

        if (!empty($data['name_id'])) { $this->name_id = (int)$data['name_id']; }

        if (!empty($data['contact_id'  ])) { $this->contact_id   = (int)$data['contact_id'  ]; }
        if (!empty($data['change_notes'])) { $this->change_notes =      $data['change_notes']; }
    }
}
