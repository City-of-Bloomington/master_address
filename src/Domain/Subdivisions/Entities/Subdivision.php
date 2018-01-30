<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Subdivisions\Entities;
use Domain\Townships\Entities\Township;

class Subdivision
{
    public $id;
    public $name;
    public $phase;
    public $status;

    public $township_id;
    public $township_name;
    public $township_code;
    public $township_quarter_code;

    public function __construct(array $data=null)
    {
        if ($data) {
            if (!empty($data['id'    ])) { $this->id     = (int)$data['id'    ]; }
            if (!empty($data['phase' ])) { $this->phase  = (int)$data['phase' ]; }
            if (!empty($data['name'  ])) { $this->name   =      $data['name'  ]; }
            if (!empty($data['status'])) { $this->status =      $data['status']; }

            if (!empty($data['township_id'  ])) { $this->township_id = (int)$data['township_id']; }
            if (!empty($data['township_name'])) { $this->township_name = $data['township_name']; }
            if (!empty($data['township_code'])) { $this->township_code = $data['township_code']; }
            if (!empty($data['township_quarter_code'])) { $this->township_quarter_code = $data['township_quarter_code']; }

        }
    }
}
