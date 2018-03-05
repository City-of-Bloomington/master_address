<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\UseCases\Correct;

use Domain\Logs\ChangeLogRequest;

class CorrectRequest implements ChangeLogRequest
{
    // Street fields
    public $street_id;
    public $town_id;
    public $notes;

    // Change log entry
    public $user_id;
    public $contact_id;
    public $change_notes;

    public function __construct(int $street_id, int $user_id, ?array $data=null)
    {
        $this->street_id = $street_id;
        $this->user_id   = $user_id;

        if (!empty($data['town_id'     ])) { $this->town_id = (int)$data['town_id'     ]; }
        if (!empty($data['notes'       ])) { $this->notes        = $data['notes'       ]; }

        if (!empty($data['contact_id'  ])) { $this->contact_id   = (int)$data['contact_id'  ]; }
        if (!empty($data['change_notes'])) { $this->change_notes =      $data['change_notes']; }
    }
}
