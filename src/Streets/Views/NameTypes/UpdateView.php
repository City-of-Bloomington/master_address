<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Application\Streets\Views\NameTypes;

use Blossom\Classes\Block;
use Blossom\Classes\Template;

class UpdateView extends Template
{
    public function __construct(array $vars)
    {
        parent::__construct('default', 'html', $vars);
        $this->blocks[] = new Block('streets/nameTypes/updateForm.inc', ['type' => $this->type]);
    }
}
