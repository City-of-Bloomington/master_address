<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\Renumber;

class AddressNumber
{
    public $address_id;
    public $street_number_prefix;
    public $street_number;
    public $street_number_suffix;

    public function __construct(?array $data=null)
    {
        if ($data) {
            if (!empty($data['address_id'          ])) { (int)$this->address_id           = $data['address_id']; }
            if (!empty($data['street_number_prefix'])) {      $this->street_number_prefix = $data['street_number_prefix']; }
            if (!empty($data['street_number'       ])) { (int)$this->street_number        = $data['street_number'       ]; }
            if (!empty($data['street_number_suffix'])) {      $this->street_number_suffix = $data['street_number_suffix']; }
        }
    }
}
