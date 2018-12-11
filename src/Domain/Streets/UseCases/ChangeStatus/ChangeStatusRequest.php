<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\UseCases\ChangeStatus;

class ChangeStatusRequest
{
    public $street_id;
    public $status;

    // Change log fields
    public $user_id;        // Person doing the change
    public $contact_id;     // Who requested the change
    public $change_notes;

    public function __construct(int $street_id, int $user_id, ?array $data=null)
    {
        $this->street_id = $street_id;
        $this->user_id   = $user_id;

        if (!empty($data['status'      ])) { $this->status       =      $data['status'      ]; }
        if (!empty($data['contact_id'  ])) { $this->contact_id   = (int)$data['contact_id'  ]; }
        if (!empty($data['change_notes'])) { $this->change_notes =      $data['change_notes']; }
    }
}
