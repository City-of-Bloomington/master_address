<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Places\Actions\Add;

class Request
{
    public $name;
    public $short_name;
    public $status;
    public $landmark_flag;
    public $vicinity;
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

    public function __construct(?array $data=null)
    {
        foreach (array_keys((array)$this) as $f) {
            if (!empty($data[$f])) {
                switch ($f) {
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
                        $this->$f = $data[$f] ? true : false;
                    break;

                    default:
                        $this->$f = $data[$f];
                }
            }
        }
    }
}
