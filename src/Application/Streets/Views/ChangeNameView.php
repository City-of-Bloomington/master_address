<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Streets\Views;

use Application\Block;
use Application\Template;

use Domain\People\Entities\Person;

use Domain\Streets\Entities\Name;
use Domain\Streets\UseCases\Info\InfoResponse;
use Domain\Streets\UseCases\ChangeName\ChangeNameRequest;

class ChangeNameView extends Template
{
    public function __construct(ChangeNameRequest $req,
                                InfoResponse      $info,
                                ?Name             $name   =null,
                                ?Person           $contact=null)
    {
        parent::__construct('default', 'html');
        $this->vars['title'] = $this->_('changeName');

        $vars = [
            'street_id'    => $info->street->id,
            'name_id'      => $req->name_id,
            'start_date'   => $req->start_date,
            'contact_id'   => $req->contact_id,
            'change_notes' => $req->change_notes
        ];
        // Display strings for *_id fields
        if ($name)    { $vars['name'        ] = $name   ->__toString(); }
        if ($contact) { $vars['contact_name'] = $contact->__toString(); }

        $this->blocks[] = new Block('streets/info.inc', ['street'=>$info->street, 'disableButtons'=>true]);
        $this->blocks[] = new Block('streets/actions/changeNameForm.inc', $vars);
        $this->blocks[] = new Block('streets/designations/list.inc',   [
            'designations'   => $info->designations,
            'disableButtons' => true
        ]);
    }
}
