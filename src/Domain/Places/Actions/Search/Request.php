<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Places\Actions\Search;

class Request
{
    public $id;
    public $name;
    public $short_name;
    public $status;
    public $landmark_flag;
    public $vicinity;
    public $dispatch_citycode;
    public $location_id;
    public $description;
    public $x;
    public $y;
    public $latitude;
    public $longitude;
    public $entity_id;
    public $category_id;
    public $type;
    public $map_label1;
    public $map_label2;
    public $comments;
    public $publish_flag;
    public $subplace_flag;

    // Pagination fields
    public $order;
    public $itemsPerPage;
    public $currentPage;

    public function __construct(?array $data=null, ?array $order=null, ?int $itemsPerPage=null, ?int $currentPage=null)
    {
        foreach (array_keys((array)$this) as $f) {
            if (!empty($data[$f])) {
                switch ($f) {
                    case 'id':
                    case 'location_id':
                    case 'x':
                    case 'y':
                    case 'entity_id':
                    case 'category_id':
                        $this->$f = (int)$data[$f];
                    break;

                    case 'latitude':
                    case 'longitude':
                        $this->$f = (float)$data[$f];
                    break;

                    case 'landmark_flag':
                    case 'publish_flag':
                    case 'subplace_flag':
                        $this->$f = $data[$f]=='Y' ? true : false;
                    break;

                    default:
                        $this->$f = $data[$f];
                }
            }
        }
        if ($order       ) { $this->order        = $order;        }
        if ($itemsPerPage) { $this->itemsPerPage = $itemsPerPage; }
        if ($currentPage ) { $this->currentPage  = $currentPage;  }
    }

    public function isEmpty(): bool
    {
        // Check the most common fields first, so we can return quickly
        // when there's stuff in this request.
        $fields = [
            'name',
            'short_name',
            'status',
            'landmark_flag',
            'vicinity',
            'dispatch_citycode',
            'location_id',
            'description',
            'x',
            'y',
            'latitude',
            'longitude',
            'entity_id',
            'category_id',
            'type',
            'map_label1',
            'map_label2',
            'comments',
            'publish_flag',
            'subplace_flag',
            'id'
        ];
        foreach ($fields as $f) {
            if ($this->$f) { return false; }
        }
        return true;
    }
}
