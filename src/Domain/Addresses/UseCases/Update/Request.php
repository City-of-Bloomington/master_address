<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\Update;

use Domain\Logs\ChangeLogRequest;

class Request implements ChangeLogRequest
{
    public $address_id;

    // Address Fields
    public $address2;
    public $address_type;
    public $jurisdiction_id;
    public $township_id;
    public $subdivision_id;
    public $plat_id;
    public $section;
    public $quarter_section;
    public $plat_lot_number;
    public $notes;

    // Location Fields
    public $mailable;
    public $occupiable;
    public $group_quarter;

    // Change Log Fields
    public $user_id;
    public $contact_id;
    public $change_notes;

    public function __construct(int $address_id, int $user_id, ?array $data=null)
    {
        $this->address_id = $address_id;
        $this->user_id    = $user_id;

        if (!empty($data['address2'       ])) { $this->address2        =      $data['address2'       ]; }
        if (!empty($data['address_type'   ])) { $this->address_type    =      $data['address_type'   ]; }
        if (!empty($data['jurisdiction_id'])) { $this->jurisdiction_id = (int)$data['jurisdiction_id']; }
        if (!empty($data['township_id'    ])) { $this->township_id     = (int)$data['township_id'    ]; }
        if (!empty($data['subdivision_id' ])) { $this->subdivision_id  = (int)$data['subdivision_id' ]; }
        if (!empty($data['plat_id'        ])) { $this->plat_id         = (int)$data['plat_id'        ]; }
        if (!empty($data['section'        ])) { $this->section         =      $data['section'        ]; }
        if (!empty($data['quarter_section'])) { $this->quarter_section =      $data['quarter_section']; }
        if (!empty($data['plat_lot_number'])) { $this->plat_lot_number =      $data['plat_lot_number']; }
        if (!empty($data['notes'          ])) { $this->notes           =      $data['notes'          ]; }

        if (!empty($data['mailable'       ])) { $this->mailable        =      $data['mailable'     ] ? true : false; }
        if (!empty($data['occupiable'     ])) { $this->occupiable      =      $data['occupiable'   ] ? true : false; }
        if (!empty($data['group_quarter'  ])) { $this->group_quarter   =      $data['group_quarter'] ? true : false; }
    }
}
