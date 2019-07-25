<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Streets\Designations\UseCases\Reorder;

class Response
{
    public $street_id;
    public $errors;

    public function __construct(?int $street_id=null, ?array $errors=null)
    {
        $this->street_id = $street_id;
        $this->errors    = $errors;
    }
}
