<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Streets\Views;

use Application\Block;
use Application\Template;

use Domain\Addresses\UseCases\Search\SearchResponse;
use Domain\Streets\UseCases\Info\InfoResponse;
use Domain\Streets\UseCases\ChangeStatus\ChangeStatusRequest;
use Domain\People\Entities\Person;

class ChangeStatusView extends Template
{
    public function __construct(ChangeStatusRequest  $request,
                                InfoResponse         $info,
                                array                $statuses,
                                SearchResponse       $addressSearch,
                                ?Person              $contact)
    {
        parent::__construct('two-column', 'html');
        $this->vars['title'] = $this->_('changeStatus');

        $vars = [
            'street_id'    => $request->street_id,
            'status'       => $request->status,
            'statuses'     => $statuses,
            'contact_id'   => $contact ? $contact->id           : null,
            'contact_name' => $contact ? $contact->__toString() : null,
            'change_notes' => parent::escape($request->change_notes)
        ];

        $this->blocks = [
            new Block('streets/actions/statusChangeForm.inc', $vars),
            new Block('streets/info.inc',              ['street'         => $info->street,
                                                        'disableButtons' => true]),
            new Block('logs/changeLog.inc',            ['entries'        => $info->changeLog->entries,
                                                        'total'          => $info->changeLog->total   ]),
            new Block('streets/designations/list.inc', ['designations'   => $info->designations,
                                                        'disableButtons' => true]),
            'panel-one' => [
                new Block('streets/addresses.inc',     ['addresses'      => $addressSearch->addresses,
                                                        'disableButtons' => true])
            ]
        ];
    }
}
