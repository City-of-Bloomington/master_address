<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
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

    /**
     * Converts ParseResponse properties to SearchRequest properties
     */
    public function toSearchQuery(): array
    {
        $query = [];
        foreach ($this as $k=>$v) {
            if ($v) {
                switch ($k) {
                    case 'direction':     $query['street_direction'     ] = $v; break;
                    case 'street_name':   $query['street_name'          ] = $v; break;
                    case 'postDirection': $query['street_post_direction'] = $v; break;
                    case 'streetType':    $query['street_suffix_code'   ] = $v; break;
                    default:
                        $query[$k] = $v;
                }
            }
        }
        return $query;
    }
}
