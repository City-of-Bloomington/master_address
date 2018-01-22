<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Auth;

use Domain\Users\Entities\User;
use Domain\Users\DataStorage\UsersRepository;

class AuthenticationService
{
    private $repo;

    public function __construct(UsersRepository $usersRepository)
    {
        $this->repo = $usersRepository;
    }

    public function identify(string $username): User
    {
        $row = $this->repo->loadByUsername($username);
        if ($row) {
            return new User($row);
        }
    }

    /**
     * Returns a User on success or null on failure
     *
     * @return User
     */
    public function authenticate(string $username, string $password)
    {
        $row = $this->repo->loadByUsername($username);
        if ($row && !empty($row['authentication_method'])) {
            switch ($row['authentication_method']) {
                case 'local':
                    if ($row['password'] == $this->password_hash($password)) {
                        return new User($row);
                    }
                break;

                default:
                    #$method = $row['authentication_method'];
                    #$class = $DIRECTORY_CONFIG[$method]['classname'];
                    #return $class::authenticate($this->getUsername(),$password);
            }
        }
    }

    public function password_hash(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }
}
