<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Streets\Views;

use Application\Block;
use Application\Template;

use Domain\Addresses\UseCases\Search\SearchResponse;
use Domain\Streets\Metadata;
use Domain\Streets\UseCases\Info\InfoResponse;
use Domain\Streets\UseCases\ChangeStatus\ChangeStatusRequest;
use Domain\People\Entities\Person;

class ChangeStatusView extends Template
{
    public function __construct(ChangeStatusRequest  $request,
                                InfoResponse         $info,
                                Metadata             $metadata,
                                SearchResponse       $addressSearch,
                                ?Person              $contact)
    {
        parent::__construct('two-column', 'html');
        $this->vars['title'] = $this->_('retire');

        $vars = [
            'street_id'    => $request->street_id,
            'status'       => $request->status,
            'statuses'     => $metadata->statuses(),
            'contact_id'   => $contact ? $contact->id           : null,
            'contact_name' => $contact ? $contact->__toString() : null,
            'change_notes' => parent::escape($request->change_notes),
        ];
        $this->blocks[] = new Block('streets/actions/statusChangeForm.inc', $vars);
        $this->blocks[] = new Block('streets/info.inc',              ['street'       => $info->street      ]);
        $this->blocks[] = new Block('logs/changeLog.inc',            ['changes'      => $info->changeLog   ]);
        $this->blocks[] = new Block('streets/designations/list.inc', ['designations' => $info->designations]);
        $this->blocks['panel-one'][] = new Block('addresses/list.inc', ['addresses' => $addressSearch->addresses]);
    }
}
