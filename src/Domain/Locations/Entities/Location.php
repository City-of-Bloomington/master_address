<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Locations\Entities;

class Location
{
    public $location_id;
    public $type_id;
    public $address_id;
    public $subunit_id;
    public $mailable;
    public $occupiable;
    public $active;
    public $trash_day;
    public $recycle_week;

    // Foreign key value from location_status
    public $status;

    // Foreign key value for type_id
    public $type_code;
    public $type_name;

    // Addresses and/or Subunits with this location_id
    public $addresses = [];
    public $subunits  = [];

    public function __construct(?array $data=null)
    {
        if ($data) { $this->setData($data); }
    }

    public function setData(array $data)
    {
        foreach (array_keys((array)$this) as $f) {
            if (!empty($data[$f])) {
                switch ($f) {
                    case 'location_id':
                    case 'type_id':
                    case 'address_id':
                    case 'subunit_id':
                        $this->$f = (int)$data[$f];
                    break;

                    case 'mailable':
                    case 'occupiable':
                    case 'active':
                        $this->$f = $data[$f] ? true : false;
                    break;

                    default:
                        $this->$f = $data[$f];
                }
            }
        }
    }
}
