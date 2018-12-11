<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Streets\Names\Views;

use Application\Block;
use Application\Template;

use Domain\Addresses\Metadata as Address;
use Domain\Streets\Metadata;
use Domain\Streets\Names\UseCases\Add\AddRequest;

class AddView extends Template
{
    public function __construct(AddRequest $req, Metadata $metadata)
    {
        parent::__construct('two-column', 'html');
        $this->vars['title'] = $this->_('streetName_add');

        $vars = (array)$req;
        $vars['directions'] = Address::$directions;
        $vars['types'     ] = $metadata->types();
        $this->blocks[] = new Block('streets/names/addForm.inc', $vars);
    }
}
