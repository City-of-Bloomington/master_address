<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Users\Entities;

trait UserFields
{
    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $username;
    public $password;
    public $role;
    public $authentication_method;
}
