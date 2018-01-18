<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Auth;

use Domain\Users\Entities\User;

class AuthorizationService
{
    const ROLE_ANONYMOUS = 'Anonymous';
    const ROLE_PUBLIC    = 'Public';
    const ROLE_ENGINEER  = 'Engineering';
    const ROLE_GIS       = 'GIS';
    const ROLE_ADMIN     = 'Administrator';

    private $permissions;

    public function __construct(array $permissions)
    {
        $this->permissions = $permissions;
    }

    public function __invoke(string $action, User $user=null)
    {
        $role = $user->role ? $user->role : self::ROLE_ANONYMOUS;

        return array_key_exists($role, $this->permissions)
            && in_array($action, $this->permissions[$role]);
    }
}
