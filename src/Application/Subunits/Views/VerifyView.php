<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Subunits\Views;

use Application\Block;
use Application\Template;

use Domain\Subunits\UseCases\Info\InfoResponse;
use Domain\Subunits\UseCases\Verify\VerifyRequest;
use Domain\People\Entities\Person;

class VerifyView extends Template
{
    public function __construct(VerifyRequest $request,
                                InfoResponse  $info,
                                ?Person       $contact=null)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('two-column', $format);

        if ($info->errors) { $_SESSION['errorMessages'] = $info->errors; }

        $vars = [
            'id'           => $request->subunit_id,
            'contact_id'   => $contact ? $contact->id           : null,
            'contact_name' => $contact ? $contact->__toString() : null,
            'change_notes' => parent::escape($request->change_notes),
            'help'         => parent::escape(sprintf($this->_('verify_statement', 'messages'), $_SESSION['USER']->getFullname())),
            'return_url'   => parent::generateUri('subunits.view', ['id'=>$request->subunit_id])
        ];

        $this->blocks = [
            new Block('generic/verifyForm.inc', $vars),
            new Block('subunits/info.inc',  ['subunit'  => $info->subunit  ]),
            new Block('logs/statusLog.inc', ['statuses' => $info->statusLog]),
            new Block('logs/changeLog.inc', ['entries'  => $info->changeLog->entries,
                                               'total'  => $info->changeLog->total]),
            'panel-one' => [
                new Block('locations/locations.inc', ['locations' => $info->locations, 'disableButton' => true])
            ]
        ];
    }
}
