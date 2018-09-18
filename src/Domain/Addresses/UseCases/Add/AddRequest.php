<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\Add;

class AddRequest
{
    public $status;
    public $street_number_prefix;
    public $street_number;
    public $street_number_suffix;
    public $street_id;
    public $address2;
    public $address_type;
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

    // Location fields
    // If there is not a location_id, then these fields
    // need to be filled in
    public $location_id;
    public $locationType_id;
    public $mailable;
    public $occupiable;
    public $active;
    public $trash_day;
    public $recycle_week;

    // Change log fields
    public $user_id;
    public $contact_id;
    public $change_notes;

    /**
     * @param int   $user_id  The authorized user maing the request
     * @param array $data     Data to populate the request
     */
    public function __construct(int $user_id, ?array $data=null)
    {
        if ($data) {
            foreach ($this as $f=>$v) {
                if (!empty($data[$f])) {
                    switch ($f) {
                        case 'street_id':
                        case 'location_id':
                        case 'plat_id':
                        case 'jurisdiction_id':
                        case 'township_id':
                        case 'subdivision_id':
                        case 'zip':
                        case 'zipplus4':
                        case 'state_plane_x':
                        case 'state_plane_y':
                        case 'locationType_id':
                            $this->$f = (int)$data[$f];
                        break;

                        case 'latitude':
                        case 'longitude':
                            $this->$f = (float)$data[$f];
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

        $this->user_id = $user_id;
    }
}
