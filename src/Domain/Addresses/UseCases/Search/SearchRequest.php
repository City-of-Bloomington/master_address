<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\Search;

class SearchRequest
{
    public $id;
    public $street_number_prefix;
    public $street_number;
    public $street_number_suffix;
    public $address2;
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

    // Street Name fields
    public $street_direction;
    public $street_name;
    public $street_post_direction;
    public $street_suffix_code;

    public $status;

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
                        case 'id':
                        case 'street_number':
                        case 'street_id':
                        case 'jurisdiction_id':
                        case 'township_id':
                        case 'subdivision_id':
                        case 'plat_id':
                        case 'zip':
                        case 'zipplus4':
                        case 'state_plane_x':
                        case 'state_plane_y':
                            $this->$k = (int)$data[$k];
                        break;

                        case 'latitude':
                        case 'longitude':
                            $this->$k = (float)$data[$k];
                        break;

                        default:
                            $this->$k = $data[$k];
                    }

                }
            }
        }
        if ($order       ) { $this->order        = $order;        }
        if ($itemsPerPage) { $this->itemsPerPage = $itemsPerPage; }
        if ($currentPage ) { $this->currentPage  = $currentPage;  }
    }

    public function isEmpty(): bool
    {
        // Check the most common fields first, so we can return quickly
        // when there's stuff in this request.
        $fields = [
            'street_number',
            'street_name',
            'id',
            'street_number_prefix',
            'street_number_suffix',
            'street_direction',
            'street_post_direction',
            'street_suffix_code',
            'address2',
            'address_type',
            'street_id',
            'jurisdiction_id',
            'township_id',
            'subdivision_id',
            'plat_id',
            'section',
            'quarter_section',
            'plat_lot_number',
            'city',
            'state',
            'zip',
            'zipplus4',
            'state_plane_x',
            'state_plane_y',
            'latitude',
            'longitude',
            'usng',
            'notes',
            'status'
        ];
        foreach ($fields as $f) {
            if ($this->$f) { return false; }
        }
        return true;
    }
}
