<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Streets\UseCases\ChangeName;

class ChangeNameRequest
{
    // Designation fields
    public $street_id;
    public $name_id;
    public $rank;
    public $start_date;

    // Change log entry
    public $user_id;
    public $contact_id;
    public $change_notes;

    public function __construct(int $street_id, int $user_id, \DateTime $start_date, ?array $data=null)
    {
        $this->street_id  = $street_id;
        $this->user_id    = $user_id;
        $this->start_date = $start_date;

        if (!empty($data['name_id'])) { $this->name_id = (int)$data['name_id']; }
        if (!empty($data['rank'   ])) { $this->rank    = (int)$data['rank'   ]; }

        if (!empty($data['contact_id'  ])) { $this->contact_id = (int)$data['contact_id'  ]; }
        if (!empty($data['change_notes'])) { $this->change_notes =    $data['change_notes']; }
    }
}
