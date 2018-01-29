<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Plats\UseCases\Search;

class SearchRequest
{
    public $id;
    public $name;
    public $township_id;
    public $plat_type;
    public $cabinet;
    public $envelope;

    public $order;
    public $itemsPerPage;
    public $currentPage;

    public function __construct(?array $data=null, ?array $order=null, ?int $itemsPerPage=null, ?int $currentPage=null)
    {
        if ($data) {
            if (!empty($data['id'         ])) { $this->id          = (int)$data['id'         ]; }
            if (!empty($data['name'       ])) { $this->name        =      $data['name'       ]; }
            if (!empty($data['township_id'])) { $this->township_id = (int)$data['township_id']; }
            if (!empty($data['plat_type'  ])) { $this->plat_type   =      $data['plat_type'  ]; }
            if (!empty($data['cabinet'    ])) { $this->cabinet     =      $data['cabinet'    ]; }
            if (!empty($data['envelope'   ])) { $this->envelope    =      $data['envelope'   ]; }
        }
        if ($order       ) { $this->order        = $order;        }
        if ($itemsPerPage) { $this->itemsPerPage = $itemsPerPage; }
        if ($currentPage ) { $this->currentPage  = $currentPage;  }
    }
}
