<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\Entities;

class Name implements \JsonSerializable
{
    public $id;
    public $direction;
    public $name;
    public $post_direction;
    public $suffix_code_id;
    public $notes;

    // Foreign key fields from street_types
    public $suffix_code;
    public $suffix_name;

    public function __construct(?array $data=null)
    {
        if (!empty($data['id'            ])) { $this->id             = (int)$data['id']; }
        if (!empty($data['direction'     ])) { $this->direction      = $data['direction'     ]; }
        if (!empty($data['name'          ])) { $this->name           = $data['name'          ]; }
        if (!empty($data['post_direction'])) { $this->post_direction = $data['post_direction']; }
        if (!empty($data['suffix_code_id'])) { $this->suffix_code_id = (int)$data['suffix_code_id']; }

        if (!empty($data['notes'      ])) { $this->notes       = $data['notes'      ]; }
        if (!empty($data['suffix_code'])) { $this->suffix_code = $data['suffix_code']; }
        if (!empty($data['suffix_name'])) { $this->suffix_name = $data['suffix_name']; }
    }

    public function __toString()
    {
        $out = [];
        if ($this->direction     ) { $out[] = $this->direction;      }
        if ($this->name          ) { $out[] = $this->name;           }
        if ($this->post_direction) { $out[] = $this->post_direction; }
        if ($this->suffix_code   ) { $out[] = $this->suffix_code;    }
        return implode(' ', $out);
    }

    public function jsonSerialize()
    {
        return array_merge((array)$this, ['streetName'=>$this->__toString()]);
    }
}
