<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Subunits\UseCases\Add;

use Domain\Locations\Metadata as Location;

class AddRequest
{
    public $address_id;

    // Subunit fields
    public $subunitType_id;
    public $identifier;
    public $notes;
    public $state_plane_x;
    public $state_plane_y;
    public $latitude;
    public $longitude;
    public $usng;
    public $subunitStatus;

    // Location fields
    public $locationType_id = Location::DEFAULT_TYPE_ID;
    public $mailable;
    public $occupiable;
    public $trash_day;
    public $recycle_week;
    public $locationStatus;

    // Change log entry
    public $user_id;
    public $contact_id;
    public $change_notes;

    public function __construct(int $user_id, array $data=null)
    {
        $this->user_id = $user_id;

        if (isset($data['user_id'])) { unset($data['user_id']); }

        foreach ($this as $f=>$v) {
            if (!empty($data[$f])) {
                switch ($f) {
                    case 'address_id':
                    case 'subunitType_id':
                    case 'state_plane_x':
                    case 'state_plane_y':
                    case 'locationType_id':
                    case 'contact_id':
                        $this->$f = (int)$data[$f];
                    break;

                    case 'latitude':
                    case 'longitude':
                        $this->$f = (float)$data[$f];
                    break;

                    case 'mailable':
                    case 'occupiable':
                        $this->$f = $data[$f] ? true : false;
                    break;

                    default:
                        $this->$f = $data[$f];
                }
            }
        }
    }
}
