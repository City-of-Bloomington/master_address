<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Subunits\UseCases\Unretire;

class UnretireResponse
{
    public $entry_id;
    public $subunit_id;
    public $location_id;
    public $errors = [];

    public function __construct(?int   $entry_id    = null,
                                ?int   $subunit_id  = null,
                                ?int   $location_id = null
                                ?array $errors      = null)
    {
        $this->entry_id    = $entry_id;
        $this->subunit_id  = $subunit_id;
        $this->location_id = $location_id;
        $this->errors      = $errors;
    }
}
