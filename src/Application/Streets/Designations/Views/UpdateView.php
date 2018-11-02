<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Streets\Designations\Views;

use Application\Block;
use Application\Template;

use Domain\Streets\Designations\UseCases\Update\UpdateRequest;
use Domain\Streets\Metadata;
use Domain\Streets\UseCases\Info\InfoResponse;

class UpdateView extends Template
{
    public function __construct(UpdateRequest $request,
                                InfoResponse  $info,
                                Metadata      $metadata,
                                ?Person       $contact=null)
    {
        parent::__construct('default', 'html');
        $this->vars['title'] = $this->_('designation_edit');

        $this->blocks[] = new Block('streets/info.inc', [
            'street'         => $info->street,
            'disableButtons' => true
        ]);
        $this->blocks[] = new Block('streets/designations/updateForm.inc', [
            'street_id'      => $info->street->id,
            'designation_id' => $request->designation_id,
            'type_id'        => $request->type_id,
            'rank'           => $request->rank,
            'start_date'     => $request->start_date,
            'contact_id'     => $contact ? $contact->id           : null,
            'contact_name'   => $contact ? $contact->__toString() : null,
            'change_notes'   => parent::escape($request->change_notes),
            'types'          => $metadata->designationTypes()
        ]);
        $this->blocks[] = new Block('streets/designations/list.inc',   [
            'designations'   => $info->designations,
            'disableButtons' => true
        ]);
    }
}
