<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Addresses\UseCases\Verify;

class VerifyRequest
{
    public $address_id;
    
    public $user_id;
    public $notes;
    
    public function __construct(int $address_id, int $user_id, ?string $notes=null)
    {
        $this->address_id = $address_id;
        $this->user_id    = $user_id;
        $this->notes      = $notes;
    }
}
