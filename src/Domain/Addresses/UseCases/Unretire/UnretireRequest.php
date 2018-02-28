<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\Unretire;

use Domain\Logs\ChangeLogRequest;

class UnretireRequest implements ChangeLogRequest
{
    public $address_id;

    public $user_id;
    public $notes;

    public function __construct(int $address_id, int $user_id, ?array $data=null)
    {
        $this->address_id = $address_id;
        $this->user_id    = $user_id;

        if (!empty($data['notes'])) { $this->notes = $data['notes']; }
    }
}
