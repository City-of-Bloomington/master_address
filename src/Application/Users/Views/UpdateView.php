<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Users\Views;

use Application\Block;
use Application\Template;

use Domain\Users\Entities\User;
use Domain\Users\UseCases\Update\UpdateResponse;

class UpdateView extends Template
{
    public function __construct(User            $user,
                                ?UpdateResponse $response,
                                array           $roles,
                                array           $authentication_methods)
    {
        parent::__construct('default', 'html');

        if ($response && $response->errors) {
            $_SESSION['errorMessages'] = $response->errors;
        }

        $this->vars['title'] = $user->id ? $this->_('user_edit') : $this->_('user_add');

        $this->blocks = [
            new Block('users/updateForm.inc', [
                'user'                   => $user,
                'title'                  => $this->vars['title'],
                'roles'                  => $roles,
                'authentication_methods' => $authentication_methods
            ])
        ];
    }
}
