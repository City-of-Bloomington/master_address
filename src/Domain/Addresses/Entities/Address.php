<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Addresses\Entities;

class Address
{
    public $id;
    public $street_number_prefix;
    public $street_number;
    public $street_number_suffix;
    public $adddress2;
    public $address_type;
    public $street_id;
    public $jurisdiction_id;
    public $township_id;
    public $subdivision_id;
    public $plat_id;
    public $section;
    public $quarter_section;
    public $plat_lot_number;
    public $city;
    public $state;
    public $zip;
    public $zipplus4;
    public $state_plane_x;
    public $state_plane_y;
    public $latitude;
    public $longitude;
    public $usng;
    public $notes;

    // Fields from foreign key tables
    public $jurisdiction_name;
    public $plat_name;
    public $township_name;
    public $subdivision_name;

    // Street Name fields
    public $street_direction;
    public $street_name;
    public $street_post_direction;
    public $street_suffix_code;

    public $status;

    public function __construct(?array $data=null)
    {
        if ($data) {
            foreach ($this as $f=>$v) {
                if (!empty($data[$f])) {
                    switch ($f) {
                        case 'id':
                        case 'street_id':
                        case 'plat_id':
                        case 'jurisdiction_id':
                        case 'township_id':
                        case 'subdivision_id':
                        case 'zip':
                        case 'zipplus4':
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

    public function __toString()
    {
        return implode(' ', [
            $this->street_number_prefix,
            $this->street_number,
            $this->street_number_suffix,
            $this->street_direction,
            $this->street_name,
            $this->street_suffix_code,
            $this->street_post_direction
        ]);
    }
}
