<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Subunits\Views;

use Application\Block;
use Application\Template;

use Domain\Subunits\Metadata;
use Domain\Subunits\UseCases\ChangeStatus\ChangeStatusRequest;
use Domain\Subunits\UseCases\Info\InfoResponse;
use Domain\People\Entities\Person;

class ChangeStatusView extends Template
{
    public function __construct(ChangeStatusRequest $request,
                                InfoResponse        $info,
                                array               $statuses,
                                ?Person             $contact)
    {
        parent::__construct('two-column', 'html');
        $this->vars['title'] = $this->_('changeStatus');

        $this->blocks = [
            new Block('subunits/actions/changeStatusForm.inc', ['subunit'      => $info->subunit,
                                                                'status'       => $request->status,
                                                                'statuses'     => $statuses,
                                                                'contact_id'   => $contact ? $contact->id           : null,
                                                                'contact_name' => $contact ? $contact->__toString() : null,
                                                                'change_notes' => parent::escape($request->change_notes)]),
            new Block('logs/statusLog.inc',  ['statuses'  => $info->statusLog]),
            new Block('logs/changeLog.inc',  ['entries'   => $info->changeLog->entries,
                                              'total'     => $info->changeLog->total])
       ];
    }
}
