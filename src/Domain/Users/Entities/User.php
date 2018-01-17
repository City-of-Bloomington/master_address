<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Domain\Users\Entities;

class User
{
    public $id;
    public $firstname;
    public $lastname;
    public $email;

    public $username;
    public $role;
    public $authentication_method;

    public function __construct(array $data=null)
    {
        if ($data) {
            foreach ($this as $k=>$v) {
                if (!empty($data[$k])) {
                    switch ($k) {
                        case 'id':
                            $this->id = (int)$data['id'];
                        break;

                        default:
                            $this->$k = $data[$k];
                    }
                }
            }
        }
    }
}
