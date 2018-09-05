<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Streets\UseCases\Alias;

class AliasResponse
{
    public $entry_id;        // The ID of the row in the log table
    public $street_id;
    public $designation_id;  // The new designation ID
    public $errors = [];

    public function __construct(?int   $entry_id       = null,
                                ?int   $street_id      = null,
                                ?int   $designation_id = null,
                                ?array $errors         = null)
    {
        $this->entry_id       = $entry_id;
        $this->street_id      = $street_id;
        $this->designation_id = $designation_id;
        $this->errors         = $errors;
    }
}
