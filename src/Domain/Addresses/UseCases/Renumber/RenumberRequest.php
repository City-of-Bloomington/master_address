<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\Renumber;

class RenumberRequest
{
    // An array of AddressNumber objects
    public $address_numbers = [];

    // Change log entry
    public $user_id;
    public $contact_id;
    public $change_notes;

    public function __construct(array $address_numbers, int $user_id, ?array $data=null)
    {
        $this->user_id         = $user_id;
        $this->address_numbers = $address_numbers;

        if (!empty($data['contact_id'  ])) { $this->contact_id = (int)$data['contact_id'  ]; }
        if (!empty($data['change_notes'])) { $this->change_notes =    $data['change_notes']; }
    }
}
