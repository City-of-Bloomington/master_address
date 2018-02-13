<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Addresses\Entities;

class Subunit
{
    public $id;
    public $address_id;
    public $type_id;
    public $identifier;
    public $notes;
    public $state_plane_x;
    public $state_plane_y;
    public $latitude;
    public $longitude;
    public $usng;
    
    // Foreign key value from subunit_status
    public $status;
    
    // Foreign key values from subunit_types
    public $type_code;
    public $type_name;
    
    public function __construct(?array $data=null)
    {
        foreach ($this as $f=>$v) {
            if (!empty($data[$f])) {
                switch ($f) {
                    case 'id':
                    case 'address_id':
                    case 'type_id':
                    case 'state_plane_x':
                    case 'state_plane_y':
                        $this->$f = (int)$data[$f];
                    break;
                    
                    case 'latitude':
                    case 'longitude':
                        $this->$f = (float)$data[$f];
                    break;
                    
                    default:
                        $this->$f = $data[$f];
                }
            }
        }
    }
}
