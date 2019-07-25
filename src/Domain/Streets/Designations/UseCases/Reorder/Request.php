<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Streets\Designations\UseCases\Reorder;

class Request
{
    public $street_id;
    public $designation_ids = []; // An array of sorted designation_ids

    public function __construct(?int $street_id=null, ?array $designation_ids=null)
    {
        $this->street_id       = $street_id;
        $this->designation_ids = $designation_ids;
    }
}
