<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\Entities;

class Designation
{
    public $id;
    public $street_id;
    public $name_id;
    public $type_id;
    public $start_date;
    public $end_date;
    public $rank;

    public $type;

    public $direction;
    public $name;
    public $post_direction;
    public $suffix_code;

    public function __construct(array $data)
    {
        if (!empty($data['id'       ])) { $this->id        = (int)$data['id'       ]; }
        if (!empty($data['street_id'])) { $this->street_id = (int)$data['street_id']; }
        if (!empty($data['name_id'  ])) { $this->name_id   = (int)$data['name_id'  ]; }
        if (!empty($data['type_id'  ])) { $this->type_id   = (int)$data['type_id'  ]; }
        if (!empty($data['rank'     ])) { $this->rank      = (int)$data['rank'     ]; }

        if (!empty($data['start_date'])) { $this->setStartDate($data['start_date']); }
        if (!empty($data['end_date'  ])) { $this->setEndDate  ($data['end_date'  ]); }

        if (!empty($data['type'          ])) { $this->type           = $data['type'          ]; }
        if (!empty($data['direction'     ])) { $this->direction      = $data['direction'     ]; }
        if (!empty($data['name'          ])) { $this->name           = $data['name'          ]; }
        if (!empty($data['post_direction'])) { $this->post_direction = $data['post_direction']; }
        if (!empty($data['suffix_code'   ])) { $this->suffix_code    = $data['suffix_code'   ]; }
    }

    public function setStartDate(\DateTime $d) { $this->start_date = $d; }
    public function setEndDate  (\DateTime $d) { $this->end_date   = $d; }

    public static function hydrate(array $row)
    {
        if (!empty($row['start_date'])) { $row['start_date'] = new \DateTime($row['start_date']); }
        if (!empty($row['end_date'  ])) { $row['end_date'  ] = new \DateTime($row['end_date'  ]); }
        return new Designation($row);
    }
}
