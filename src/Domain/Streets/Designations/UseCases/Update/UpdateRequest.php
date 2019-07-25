<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Streets\Designations\UseCases\Update;

class UpdateRequest
{
    // Designation fields
    public $designation_id;
    public $type_id;
    public $start_date;

    // Change log entry
    public $user_id;
    public $contact_id;
    public $change_notes;

    public function __construct(int $designation_id, int $user_id, \DateTime $start_date, ?array $data=null)
    {
        $this->designation_id  = $designation_id;
        $this->user_id         = $user_id;
        $this->start_date      = $start_date;

        if (!empty($data['type_id'     ])) { $this->type_id      = (int)$data['type_id'     ]; }

        if (!empty($data['contact_id'  ])) { $this->contact_id   = (int)$data['contact_id'  ]; }
        if (!empty($data['change_notes'])) { $this->change_notes =      $data['change_notes']; }
    }
}
