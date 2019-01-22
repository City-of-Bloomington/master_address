<?php
/**
 * @copyright 2017-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Streets\Views;

use Application\Block;
use Application\Template;

use Domain\Addresses\UseCases\Search\SearchResponse;
use Domain\Streets\UseCases\Info\InfoResponse;
use Domain\Streets\UseCases\Update\UpdateRequest;
use Domain\Streets\Metadata;
use Domain\People\Entities\Person;

class UpdateView extends Template
{
    public function __construct(UpdateRequest  $request,
                                InfoResponse   $info,
                                Metadata       $metadata,
                                SearchResponse $addressSearch,
                                ?Person        $contact=null)
    {
        parent::__construct('two-column', 'html');
        $this->vars['title'] = $this->_('update');

        $vars = ['towns' => $metadata->towns()];
        foreach ($request as $k=>$v) { $vars[$k] = parent::escape($v); }
        if ($contact && $contact->id === $request->contact_id) {
            $vars['contact_name'] = $contact->__toString();
        }

        $this->blocks = [
            new Block('streets/actions/updateForm.inc', $vars),
            new Block('logs/changeLog.inc',            ['entries'      => $info->changeLog->entries,
                                                        'total'        => $info->changeLog->total   ]),
            new Block('streets/designations/list.inc', ['designations' => $info->designations,
                                                        'street_id'    => $info->street->id,
                                                        'disableButtons' => true
                                                       ]),
            'panel-one' => [
                new Block('streets/addresses.inc', ['addresses'      => $addressSearch->addresses,
                                                    'disableButtons' => true])
            ]
        ];
    }
}
