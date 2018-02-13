<?php
/**
 * @copyright 2017-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Streets\Views;

use Application\Block;
use Application\Template;

use Domain\Streets\UseCases\Info\InfoResponse;
use Domain\Streets\UseCases\Verify\VerifyRequest;

class VerifyView extends Template
{
    public function __construct(VerifyRequest $request, InfoResponse $info)
    {
        parent::__construct('two-column', 'html');
        $this->vars['title'] = $this->_('verify');

        $this->blocks[] = new Block('streets/actions/verifyForm.inc', ['request' => $request]);
        $this->blocks[] = new Block('changeLogs/changeLog.inc', ['changes' => $info->changeLog]);
        $this->blocks[] = new Block('streets/designations.inc', [
            'street'       => $info->street,
            'designations' => $info->designations
        ]);
    }
}
