<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Streets\UseCases\Intersections;

class IntersectionsRequest
{
    public $street_id_1;
    public $street_id_2;

    public function __construct(?array $data=null)
    {
        if (!empty($data['street_id_1'])) { $this->street_id_1 = (int)$data['street_id_1']; }
        if (!empty($data['street_id_2'])) { $this->street_id_2 = (int)$data['street_id_2']; }
    }
}
