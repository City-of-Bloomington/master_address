<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Subunits\UseCases\Activate;

class Request
{
    public $location_id;
    public $subunit_id;

    // Change log fields
    public $user_id;
    public $contact_id;
    public $change_notes;

    public function __construct(int $subunit_id, int $location_id, int $user_id, ?array $data=null)
    {
        $this->subunit_id  = $subunit_id;
        $this->location_id = $location_id;
        $this->user_id     = $user_id;

        if (!empty($data['contact_id'  ])) { $this->contact_id   = (int)$data['contact_id'  ]; }
        if (!empty($data['change_notes'])) { $this->change_notes =      $data['change_notes']; }
    }
}
