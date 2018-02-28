<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Streets\Views;

use Application\Block;
use Application\Template;

use Domain\Addresses\UseCases\Search\SearchResponse;
use Domain\Streets\UseCases\Info\InfoResponse;
use Domain\Streets\UseCases\Retire\RetireRequest;

class RetireView extends Template
{
    public function __construct(RetireRequest $request, InfoResponse $info, SearchResponse $addressSearch)
    {
        parent::__construct('two-column', 'html');
        $this->vars['title'] = $this->_('retire');

        $this->blocks[] = new Block('generic/retireForm.inc', [
            'id'         => $request->street_id,
            'notes'      => parent::escape($request->notes),
            'help'       => parent::escape(sprintf($this->_('retire_statement', 'messages'), $_SESSION['USER']->getFullname())),
            'return_url' => parent::generateUri('streets.view', ['id'=>$request->street_id])
        ]);
        $this->blocks[] = new Block('streets/info.inc',         ['street'  => $info->street   ]);
        $this->blocks[] = new Block('logs/changeLog.inc', ['changes' => $info->changeLog]);
        $this->blocks[] = new Block('streets/designations.inc', [
            'street'       => $info->street,
            'designations' => $info->designations
        ]);
        $this->blocks['panel-one'][] = new Block('addresses/list.inc', ['addresses' => $addressSearch->addresses]);
    }
}
