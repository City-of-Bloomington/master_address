<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Subunits\UseCases\Unretire;

use Domain\Logs\ChangeLogRequest;

class UnretireRequest implements ChangeLogRequest
{
    public $subunit_id;

    public $user_id;
    public $contact_id;
    public $change_notes;

    public function __construct(int $subunit_id, int $user_id, ?array $data=null)
    {
        $this->subunit_id = $subunit_id;
        $this->user_id    = $user_id;

        if (!empty($data['contact_id'  ])) { $this->contact_id = (int)$data['contact_id'  ]; }
        if (!empty($data['change_notes'])) { $this->change_notes =    $data['change_notes']; }
    }
}
