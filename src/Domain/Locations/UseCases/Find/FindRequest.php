<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Locations\UseCases\Find;

class FindRequest
{
    public $location_id;
    public $type_id;
    public $address_id;
    public $subunit_id;
    public $mailable;
    public $occupiable;
    public $group_quarter;
    public $active;

    public function __construct(?array $data=null)
    {
        if ($data) {
            foreach (array_keys((array)$this) as $k) {
                if (!empty($data[$k])) {
                    switch ($k) {
                        case 'location_id':
                        case 'type_id':
                        case 'address_id':
                        case 'subunit_id':
                            $this->$k = (int)$data[$k];
                        break;

                        case 'mailable':
                        case 'occupiable':
                        case 'group_quarter':
                        case 'active':
                            $this->$k = $data[$k] ? true : false;
                        break;
                    }
                }
            }
        }
    }
}
