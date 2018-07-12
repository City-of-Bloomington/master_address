<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\Names\UseCases\Add;

class AddRequest
{
    public $direction;
    public $name;
    public $post_direction;
    public $suffix_code_id;
    public $notes;

    public function __construct(?array $data=null)
    {
        if (!empty($data['direction'     ])) { $this->direction      =      $data['direction'     ]; }
        if (!empty($data['name'          ])) { $this->name           =      $data['name'          ]; }
        if (!empty($data['post_direction'])) { $this->post_direction =      $data['post_direction']; }
        if (!empty($data['suffix_code_id'])) { $this->suffix_code_id = (int)$data['suffix_code_id']; }
        if (!empty($data['notes'         ])) { $this->notes          =      $data['notes'         ]; }
    }
}
