<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Views\Generic;

use Blossom\Classes\Block;
use Blossom\Classes\Template;

class UpdateView extends Template
{
    public function __construct(array $vars)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format, $vars);

		$this->blocks[] = new Block("{$this->form}", $vars);
    }
}
