<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\Retire;

class RetireResponse
{
    public $entry_id;          // ID of the log entry row
    public $address_id;
    public $subunit_ids  = []; // Subunits  that were retired
    public $location_ids = []; // Locations that were retired
    public $errors       = [];

    public function __construct(?int   $entry_id     = null,
                                ?int   $address_id   = null,
                                ?array $subunit_ids  = null,
                                ?array $location_ids = null,
                                ?array $errors       = null)
    {
        $this->entry_id     = $entry_id;
        $this->address_id   = $address_id;
        $this->subunit_ids  = $subunit_ids;
        $this->location_ids = $location_ids;
        $this->errors       = $errors;
    }
}
