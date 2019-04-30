<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Addresses\Views;

use Application\Block;
use Application\Template;

use Domain\Addresses\Metadata;
use Domain\Addresses\UseCases\Update\Request;
use Domain\Addresses\UseCases\Info\InfoResponse;
use Domain\People\Entities\Person;

class UpdateView extends Template
{
    public function __construct(Request      $request,
                                Metadata     $metadata,
                                InfoResponse $info,
                                ?Person      $contact=null)
    {
        parent::__construct('two-column', 'html');
        $this->vars['title'] = $this->_('update');

        $this->blocks[] = new Block('addresses/breadcrumbs.inc', ['address'=>$info->address]);

        $vars = [
            'jurisdictions'   => $metadata->jurisdictions(),
            'quarterSections' => $metadata->quarterSections(),
            'sections'        => $metadata->sections(),
            'types'           => $metadata->types(),
            'townships'       => $metadata->townships(),
            'contact_id'   => $contact ? $contact->id           : null,
            'contact_name' => $contact ? $contact->__toString() : null,
        ];
        foreach ($request as $k=>$v) { $vars[$k] = is_string($v) ? parent::escape($v) : $v; }

        $this->blocks = [
            new Block('addresses/breadcrumbs.inc',   ['address'   =>$info->address]),
            new Block('addresses/actions/updateForm.inc', $vars),
            new Block('logs/statusLog.inc',          ['statuses'  => $info->statusLog]),
            new Block('logs/changeLog.inc',          ['entries'   => $info->changeLog->entries,
                                                      'total'     => $info->changeLog->total]),
            'panel-one' => [
                new Block('locations/locations.inc', ['locations' => $info->locations, 'disableButtons' => true]),
                new Block('subunits/list.inc',       ['address'   => $info->address,
                                                      'subunits'  => $info->subunits,
                                                      'disableButtons' => true])
            ]
        ];
    }
}
