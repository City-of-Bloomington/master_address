<?php
/**
 * @copyright 2017-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Streets\Views;

use Application\Block;
use Application\Template;

use Domain\Addresses\UseCases\Search\SearchResponse;
use Domain\Streets\UseCases\Info\InfoResponse;
use Domain\Streets\UseCases\Verify\VerifyRequest;
use Domain\People\Entities\Person;

class VerifyView extends Template
{
    public function __construct(VerifyRequest $request,
                                InfoResponse   $info,
                                SearchResponse $addressSearch,
                                ?Person        $contact)
    {
        parent::__construct('two-column', 'html');
        $this->vars['title'] = $this->_('verify');

        $this->blocks[] = new Block('generic/verifyForm.inc', [
            'id'           => $request->street_id,
            'contact_id'   => $contact ? $contact->id           : null,
            'contact_name' => $contact ? $contact->__toString() : null,
            'change_notes' => parent::escape($request->change_notes),
            'help'         => parent::escape(sprintf($this->_('verify_statement', 'messages'), $_SESSION['USER']->getFullname())),
            'return_url'   => parent::generateUri('streets.view', ['id'=>$request->street_id])
        ]);
        $this->blocks[] = new Block('streets/info.inc',                ['street'       => $info->street      ]);
        $this->blocks[] = new Block('logs/changeLog.inc',              ['changes'      => $info->changeLog   ]);
        $this->blocks[] = new Block('streets/designations/list.inc',   ['designations' => $info->designations]);
        $this->blocks['panel-one'][] = new Block('addresses/list.inc', ['addresses' => $addressSearch->addresses]);
    }
}
