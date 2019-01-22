<?php
/**
 * @copyright 2017-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Streets\Views;

use Application\Block;
use Application\Template;

use Domain\People\Entities\Person;
use Domain\Streets\Metadata;
use Domain\Streets\Entities\Name;
use Domain\Streets\UseCases\Info\InfoResponse;
use Domain\Streets\UseCases\Alias\AliasRequest;

class AliasView extends Template
{
    public function __construct(AliasRequest $request,
                                InfoResponse $info,
                                Metadata     $metadata,
                                ?Name        $name=null,
                                ?Person      $contact=null)
    {
        parent::__construct('two-column', 'html');
        $this->vars['title'] = $this->_('alias');

        $vars = [
            'street_id'    => $request->street_id,
            'name_id'      => $request->name_id,
            'type_id'      => $request->type_id,
            'rank'         => $request->rank,
            'start_date'   => $request->start_date,
            'contact_id'   => $contact ? $contact->id           : null,
            'contact_name' => $contact ? $contact->__toString() : null,
            'change_notes' => parent::escape($request->change_notes),
            'return_url'   => parent::generateUri('streets.view', ['id'=>$request->street_id]),
            'name'         => parent::escape($name),
            'types'        => $metadata->designationTypes()
        ];

        $this->blocks = [
            new Block('streets/actions/aliasForm.inc', $vars),
            new Block('streets/info.inc',              ['street'         => $info->street,
                                                        'disableButtons' => true]),
            new Block('logs/changeLog.inc',            ['entries'        => $info->changeLog->entries,
                                                        'total'          => $info->changeLog->total]),
            new Block('streets/designations/list.inc', ['designations'   => $info->designations,
                                                        'disableButtons' => true])
        ];
    }
}
