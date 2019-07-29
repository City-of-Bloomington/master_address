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
use Domain\Addresses\UseCases\Verify\VerifyRequest;
use Domain\People\Entities\Person;

class VerifyView extends Template
{
    public function __construct(VerifyRequest $request,
                                InfoResponse  $info,
                                ?Person       $contact=null)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('two-column', $format);
        $this->vars['title'] = $this->_('verify');

        $vars = [
            'id'           => $request->address_id,
            'contact_id'   => $contact ? $contact->id           : null,
            'contact_name' => $contact ? $contact->__toString() : null,
            'change_notes' => parent::escape($request->change_notes),
            'help'         => parent::escape(sprintf($this->_('verify_statement', 'messages'), $_SESSION['USER']->getFullname())),
            'return_url'   => parent::generateUri('addresses.view', ['id'=>$request->address_id])
        ];

        $this->blocks = [
            new Block('addresses/breadcrumbs.inc',   ['address'   => $info->address]),
            new Block('generic/verifyForm.inc',      $vars),
            new Block('addresses/info.inc',          ['address'   => $info->address,  'disableButtons'=>true,
                                                      'title'     => $info->address->__toString()]),
            new Block('logs/statusLog.inc',          ['statuses'  => $info->statusLog]),
            new Block('logs/changeLog.inc',          ['entries'   => $info->changeLog->entries,
                                                      'total'     => $info->changeLog->total]),
            'panel-one' => [
                new Block('locations/locations.inc', ['locations' => $info->locations, 'disableButtons'=>true])
            ]
        ];
        if ($info->subunits) {
            $this->blocks['panel-one'][] = new Block('subunits/list.inc', ['subunits' => $info->subunits]);
        }
    }
}
