<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Locations\UseCases\Search;

class SearchRequest
{
    public $location_id;
    public $type_id;
    public $address_id;
    public $subunit_id;
    public $mailable;
    public $occupiable;
    public $group_quarter;
    public $active;

    // Address fields from a Parsed Address
    public $street_number_prefix;
    public $street_number;
    public $street_number_suffix;
    public $direction;
    public $street_name;
    public $streetType;
    public $postDirection;
    public $subunitType;
    public $subunitIdentifier;
    public $city;
    public $state;
    public $zip;
    public $zipplus4;

    // Pagination fields
    public $order;
    public $itemsPerPage;
    public $currentPage;

    public function __construct(?array $data=null, ?array $order=null, ?int $itemsPerPage=null, ?int $currentPage=null)
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

                        default:
                            $this->$k = $data[$k];
                    }
                }
            }
        }
        $this->order        = $order;
        $this->itemsPerPage = $itemsPerPage;
        $this->currentPage  = $currentPage;
    }

    public function isEmpty(): bool
    {
        $fields = [
            'location_id',
            'type_id',
            'address_id',
            'subunit_id',
            'mailable',
            'occupiable',
            'group_quarter',
            'active',
            'street_number_prefix',
            'street_number',
            'street_number_suffix',
            'direction',
            'street_name',
            'streetType',
            'postDirection',
            'subunitType',
            'subunitIdentifier',
            'city',
            'state',
            'zip',
            'zipplus4'
        ];

        foreach ($fields as $f) {
            if ($this->$f) { return false; }
        }
        return true;
    }
}
