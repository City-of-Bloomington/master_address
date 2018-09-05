<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\Unretire;

class UnretireResponse
{
    public $entry_id;     // The ID of the row in the log tabke
    public $address_id;
    public $location_ids; // The location that was unretired
    public $errors = [];

    public function __construct(?int $entry_id    = null,
                                ?int $address_id  = null,
                                ?int $location_id = null,
                                ?array $errors    = null)
    {
        $this->entry_id    = $entry_id;
        $this->address_id  = $address_id;
        $this->location_id = $location_ids;
        $this->errors      = $errors;
    }
}
