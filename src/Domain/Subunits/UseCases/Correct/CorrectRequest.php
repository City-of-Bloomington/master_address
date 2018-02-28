<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Subunits\UseCases\Correct;

use Domain\Logs\ChangeLogRequest;

class CorrectRequest implements ChangeLogRequest
{
    // Subunit fields
    public $subunit_id;
    public $type_id;
    public $identifier;
    public $notes;

    // Change log entry
    public $user_id;
    public $change_notes;

    public function __construct(int $subunit_id, int $user_id, ?array $data=null)
    {
        $this->subunit_id = $subunit_id;
        $this->user_id    = $user_id;

        if (!empty($data['type_id'     ])) { $this->type_id = (int)$data['type_id'     ]; }
        if (!empty($data['identifier'  ])) { $this->identifier   = $data['identifier'  ]; }
        if (!empty($data['notes'       ])) { $this->notes        = $data['notes'       ]; }
        if (!empty($data['change_notes'])) { $this->change_notes = $data['change_notes']; }
    }
}
