<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Subunits\UseCases\Add;

class AddRequest
{
    public $address_id;
    public $status;

    // Subunit fields
    public $type_id;
    public $identifier;
    public $notes;

    // Location fields
    public $locationType_id;
    public $mailable;
    public $occupiable;
    public $group_quarter;
    public $trash_day;
    public $recycle_week;

    // Change log entry
    public $user_id;
    public $contact_id;
    public $change_notes;

    public function __construct(int $user_id, array $data=null)
    {
        $this->user_id = $user_id;

        if (isset($data['user_id'])) { unset($data['user_id']); }

        foreach (array_keys((array)$this) as $f) {
            if (!empty($data[$f])) {
                switch ($f) {
                    case 'address_id':
                    case 'type_id':
                    case 'locationType_id':
                    case 'contact_id':
                        $this->$f = (int)$data[$f];
                    break;

                    case 'mailable':
                    case 'occupiable':
                    case 'group_quarter':
                        $this->$f = $data[$f] ? true : false;
                    break;

                    default:
                        $this->$f = $data[$f];
                }
            }
        }
    }
}
