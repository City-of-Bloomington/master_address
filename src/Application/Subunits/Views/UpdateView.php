<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Subunits\Views;

use Application\Block;
use Application\Template;

use Domain\Subunits\UseCases\Info\InfoResponse;
use Domain\Subunits\UseCases\Update\Request;
use Domain\People\Entities\Person;

class UpdateView extends Template
{
    public function __construct(Request       $request,
                                InfoResponse  $info,
                                ?Person       $contact=null)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('two-column', $format);

        $vars = [
            'subunit_id'   => $request->subunit_id,
            'subunit'      => $info->subunit,
            'notes'        => parent::escape($request->notes),
            'contact_id'   => $contact ? $contact->id           : null,
            'contact_name' => $contact ? $contact->__toString() : null,
            'change_notes' => parent::escape($request->change_notes),
        ];

        $this->blocks = [
            new Block('subunits/actions/updateForm.inc', $vars),
            new Block('logs/statusLog.inc', ['statuses' => $info->statusLog]),
            new Block('logs/changeLog.inc', ['entries'  => $info->changeLog->entries,
                                               'total'  => $info->changeLog->total]),
            'panel-one' => [
                new Block('locations/locations.inc', ['locations' => $info->locations, 'disableButtons' => true])
            ]
        ];
    }
}
