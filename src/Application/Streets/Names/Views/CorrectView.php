<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Streets\Names\Views;

use Application\Block;
use Application\Template;

use Domain\Streets\Names\UseCases\Correct\CorrectRequest;
use Domain\Streets\Names\UseCases\Info\InfoResponse;
use Domain\Addresses\Metadata as Address;
use Domain\Streets\Metadata;

class CorrectView extends Template
{
    public function __construct(CorrectRequest $request, InfoResponse $info, Metadata $metadata)
    {
        parent::__construct('two-column', 'html');
        $this->vars['title'] = $this->_('correct');

        $vars = [
            'directions' => Address::$directions,
            'types'      => $metadata->types()
        ];
        foreach ($request as $k=>$v) { $vars[$k] = parent::escape($v); }

        $this->blocks[] = new Block('streets/names/correctForm.inc', $vars);
        $this->blocks[] = new Block('streets/designations/list.inc', ['designations' => $info->designations]);
    }
}
