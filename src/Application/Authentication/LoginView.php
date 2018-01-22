<?php
/**
 * @copyright 2016-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Authentication;

use Application\Block;
use Application\Template;

class LoginView extends Template
{
    public function __construct(array $vars=null)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format, $vars);

        $this->blocks[] = new Block('loginForm.inc', ['return_url'=>$this->return_url]);
    }
}
