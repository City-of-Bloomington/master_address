<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain;

use Domain\Authorization\AuthorizationService;

abstract class UseCase
{
    protected $auth;

    public function __contruct(AuthorizationService $auth)
    {
        $this->auth = $auth;
    }

    public function authorizationCheck(User $user)
    {
        $auth = $this->auth;
        $auth(get_class($this), $user);
    }
}
