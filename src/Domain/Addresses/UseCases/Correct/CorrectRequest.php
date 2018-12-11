<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\Correct;

use Domain\Logs\ChangeLogRequest;

class CorrectRequest implements ChangeLogRequest
{
    public $address_id;
    public $street_id;
    public $street_number_prefix;
    public $street_number;
    public $street_number_suffix;
    public $zip;
    public $zipplus4;
    public $notes;

    public $user_id;
    public $contact_id;
    public $change_notes;

    public function __construct(int $address_id, int $user_id, ?array $data=null)
    {
        $this->address_id = $address_id;
        $this->user_id    = $user_id;

        if (!empty($data['street_id'           ])) { $this->street_id     = (int)$data['street_id'    ]; }
        if (!empty($data['street_number'       ])) { $this->street_number = (int)$data['street_number']; }
        if (!empty($data['zip'                 ])) { $this->zip           = (int)$data['zip'          ]; }
        if (!empty($data['zipplus4'            ])) { $this->zipplus4      = (int)$data['zipplus4'     ]; }
        if (!empty($data['street_number_prefix'])) { $this->street_number_prefix = $data['street_number_prefix']; }
        if (!empty($data['street_number_suffix'])) { $this->street_number_suffix = $data['street_number_suffix']; }
        if (!empty($data['notes'       ])) { $this->notes        =    $data['notes'       ]; }
        if (!empty($data['contact_id'  ])) { $this->contact_id = (int)$data['contact_id'  ]; }
        if (!empty($data['change_notes'])) { $this->change_notes =    $data['change_notes']; }
    }
}
