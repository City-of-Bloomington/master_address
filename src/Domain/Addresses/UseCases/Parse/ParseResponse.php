<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\Parse;

class ParseResponse implements \JsonSerializable
{
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
    
    /**
     * Removes all null values
     */
    public function jsonSerialize()
    {
        return array_filter((array)$this);
    }
}
