<?php
/**
 * @copyright 2017-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Addresses\Views;

use Application\Block;
use Application\Template;

use Domain\Addresses\UseCases\Info\InfoResponse;
use Domain\Addresses\UseCases\Unretire\UnretireRequest;

class UnretireView extends Template
{
    public function __construct(UnretireRequest $request, InfoResponse $info)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('two-column', $format);
        $this->vars['title'] = $this->_('unretire');

        $this->blocks[] = new Block('generic/unretireForm.inc', [
            'id'         => $request->address_id,
            'notes'      => parent::escape($request->notes),
            'help'       => parent::escape(sprintf($this->_('unretire_statement', 'messages'), $_SESSION['USER']->getFullname())),
            'return_url' => parent::generateUri('addresses.view', ['id'=>$request->address_id])
        ]);
        $this->blocks[] = new Block('addresses/info.inc', [
            'address' => $info->address,
            'title'   => $info->address->__toString()
        ]);
        $this->blocks[]              = new Block('addresses/statusLog.inc',  ['statuses'  => $info->statusLog]);
        $this->blocks[]              = new Block('changeLogs/changeLog.inc', ['changes'   => $info->changeLog]);
        $this->blocks['panel-one'][] = new Block('locations/locations.inc',  ['locations' => $info->locations]);
        $this->blocks['panel-one'][] = new Block('subunits/list.inc',        ['address'   => $info->address, 'subunits' => $info->subunits]);
    }
}
