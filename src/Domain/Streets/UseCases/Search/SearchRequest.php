<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\UseCases\Search;

class SearchRequest
{
    // Fields from streets
    public $id;
    public $status;
    public $town_id;

    // Fields from street_names
    public $direction;
    public $name;
    public $post_direction;
    public $suffix_code;

    // Pagination fields
    public $order;
    public $itemsPerPage;
    public $currentPage;

    public function __construct(?array $data=null, ?array $order=null, ?int $itemsPerPage=null, ?int $currentPage=null)
    {
        if ($data) {
            if (!empty($data['id'     ])) { $this->id      = (int)$data['id'     ]; }
            if (!empty($data['status' ])) { $this->status  =      $data['status' ]; }
            if (!empty($data['town_id'])) { $this->town_id = (int)$data['town_id']; }

            if (!empty($data['direction'     ])) { $this->direction      = $data['direction'     ]; }
            if (!empty($data['name'          ])) { $this->name           = $data['name'          ]; }
            if (!empty($data['post_direction'])) { $this->post_direction = $data['post_direction']; }
            if (!empty($data['suffix_code'   ])) { $this->suffix_code    = $data['suffix_code'   ]; }
        }
        if ($order       ) { $this->order        = $order;        }
        if ($itemsPerPage) { $this->itemsPerPage = $itemsPerPage; }
        if ($currentPage ) { $this->currentPage  = $currentPage;  }
    }
}
