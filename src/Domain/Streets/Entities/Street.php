<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\Entities;

class Street
{
    public $id;
    public $status;
    public $notes;

    public $town_id;
    public $town_name;
    public $town_code;

    public $name_id;
    public $direction;
    public $name;
    public $post_direction;
    public $suffix_code;

    public function __construct(?array $data=null)
    {
        if ($data) {
            if (!empty($data['id'       ])) { $this->id = (int)$data['id'    ]; }
            if (!empty($data['status'   ])) { $this->status =  $data['status']; }
            if (!empty($data['notes'    ])) { $this->notes  =  $data['notes' ]; }

            if (!empty($data['town_id'  ])) { $this->town_id = (int)$data['town_id'  ]; }
            if (!empty($data['town_name'])) { $this->town_name =    $data['town_name']; }
            if (!empty($data['town_code'])) { $this->town_code =    $data['town_code']; }

            if (!empty($data['name_id'       ])) { $this->name_id  =  (int)$data['name_id']; }
            if (!empty($data['direction'     ])) { $this->direction      = $data['direction']; }
            if (!empty($data['name'          ])) { $this->name           = $data['name']; }
            if (!empty($data['post_direction'])) { $this->post_direction = $data['post_direction']; }
            if (!empty($data['suffix_code'   ])) { $this->suffix_code    = $data['suffix_code']; }
        }
    }
}
