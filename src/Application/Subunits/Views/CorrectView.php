<?php
/**
 * @copyright 2017-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Subunits\Views;

use Application\Block;
use Application\Template;

use Domain\Subunits\Metadata;
use Domain\Subunits\UseCases\Info\InfoResponse;
use Domain\Subunits\UseCases\Correct\CorrectRequest;
use Domain\People\Entities\Person;

class CorrectView extends Template
{
    public function __construct(CorrectRequest $request,
                                InfoResponse   $info,
                                Metadata       $metadata,
                                ?Person        $contact=null)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('two-column', $format);
        $this->vars['title'] = $this->_('correct');

        $vars = [
            'types'        => $metadata->types(),
            'contact_id'   => $contact ? $contact->id           : null,
            'contact_name' => $contact ? $contact->__toString() : null,
            'change_notes' => parent::escape($request->change_notes),
        ];
        foreach ($request as $k=>$v) { $vars[$k] = parent::escape($v); }
        $this->blocks = [
            new Block('subunits/actions/correctForm.inc', $vars),
            new Block('logs/statusLog.inc', ['statuses' => $info->statusLog]),
            new Block('logs/changeLog.inc', ['entries'  => $info->changeLog->entries,
                                               'total'  => $info->changeLog->total]),
            'panel-one' => [
                new Block('locations/locations.inc', ['locations' => $info->locations, 'disableButtons' => true])
            ]
        ];
    }
}
