<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Towns\Entities;

class Town
{
    public $id;
    public $name;

    public function __construct(array $data=null)
    {
        if ($data) {
            if (!empty($data['id'  ])) { $this->id   = $data['id'  ]; }
            if (!empty($data['name'])) { $this->name = $data['name']; }
        }
    }
}
