<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Authorization;

use Domain\Users\Entities\User;
use Domain\Users\DataStorage\UsersRepository;

class IdentityService
{
    private $repo;

    public function __construct(UsersRepository $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke(string $username): User
    {
        return $this->repo->loadByUsername($username);
    }
}
