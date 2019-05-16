<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Subunits\UseCases\Update;

class Request
{
    public $subunit_id;
    public $notes;

    // Location Fields
    public $mailable;
    public $occupiable;
    public $group_quarter;

    // Change Log Fields
    public $user_id;
    public $contact_id;
    public $change_notes;

    public function __construct(int $subunit_id, int $user_id, ?array $data=null)
    {
        $this->subunit_id = $subunit_id;
        $this->user_id    = $user_id;

        if (!empty($data['notes'        ])) { $this->notes         =      $data['notes']; }
        if (!empty($data['mailable'     ])) { $this->mailable      =      $data['mailable'     ] ? true : false; }
        if (!empty($data['occupiable'   ])) { $this->occupiable    =      $data['occupiable'   ] ? true : false; }
        if (!empty($data['group_quarter'])) { $this->group_quarter =      $data['group_quarter'] ? true : false; }
        if (!empty($data['contact_id'   ])) { $this->contact_id    = (int)$data['contact_id'  ]; }
        if (!empty($data['change_notes' ])) { $this->change_notes  =      $data['change_notes']; }
    }
}
