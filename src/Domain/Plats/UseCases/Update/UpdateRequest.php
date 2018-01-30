<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Plats\UseCases\Update;

class UpdateRequest
{
    public $id;
    public $name;
    public $plat_type;
    public $cabinet;
    public $envelope;
    public $start_date;
    public $end_date;
    public $notes;
    public $township_id;

    public function __construct(?array $data=null)
    {
        if ($data) {
            if (!empty($data['id'         ])) { $this->id   = (int)$data['id'       ]; }
            if (!empty($data['name'       ])) { $this->name      = $data['name'     ]; }
            if (!empty($data['plat_type'  ])) { $this->plat_type = $data['plat_type']; }
            if (!empty($data['cabinet'    ])) { $this->cabinet   = $data['cabinet'  ]; }
            if (!empty($data['envelope'   ])) { $this->envelope  = $data['envelope' ]; }
            if (!empty($data['notes'      ])) { $this->notes     = $data['notes'    ]; }
            if (!empty($data['start_date' ])) { $this->setStart_date($data['start_date']); }
            if (!empty($data['end_date'   ])) { $this->setEnd_date  ($data['end_date'  ]); }
            if (!empty($data['township_id'])) { $this->township_id = (int)$data['township_id']; }
        }
    }
    public function setStart_date (\DateTime $d) { $this->start_date  = $d; }
    public function setEnd_date   (\DateTime $d) { $this->end_date    = $d; }
}
