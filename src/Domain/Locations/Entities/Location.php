<?php
/**
 * @copyright 2018-2021 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
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
    public $group_quarter;
    public $active;

    // Fields from foreign key tables
    public $address_status;
    public $subunit_status;
    public $type_code;
    public $type_name;
    public $trash_day;
    public $recycle_week;
    public $jurisdiction_name;

    // String representations for Address and Subunit
    public $address = '';
    public $subunit = '';
    public $streetAddress;
    public $address_type;
    public $city;
    public $state;
    public $zip;
    public $latitude;
    public $longitude;

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
                    case 'zip':
                        $this->$f = (int)$data[$f];
                    break;

                    case 'mailable':
                    case 'occupiable':
                    case 'group_quarter':
                    case 'active':
                        $this->$f = $data[$f] ? true : false;
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
