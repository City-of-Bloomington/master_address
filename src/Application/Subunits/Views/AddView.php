<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Subunits\Views;

use Application\Block;
use Application\Template;

use Domain\Addresses\UseCases\Info\InfoResponse;
use Domain\People\Entities\Person;

use Domain\Logs\Metadata      as Log;
use Domain\Locations\Metadata as LocationMetadata;
use Domain\Subunits\Metadata  as SubunitMetadata;

use Domain\Subunits\UseCases\Add\AddRequest;


class AddView extends Template
{
    public function __construct(AddRequest       $request,
                                InfoResponse     $info,
                                SubunitMetadata  $subunitMetadata,
                                LocationMetadata $locationMetadata,
                                ?Person          $contact=null)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('two-column', $format);
        $this->vars['title'] = $this->_(Log::ACTION_ADD);

        $vars = [
             'subunitTypes' =>  $subunitMetadata->types(),
            'locationTypes' => $locationMetadata->types(),
            'trashDays'     => $locationMetadata->trashDays(),
            'recycleWeeks'  => $locationMetadata->recycleWeeks(),
            'statuses'      => Log::$statuses
        ];
        foreach ($request as $k=>$v) { $vars[$k] = parent::escape($v); };
        if ($contact && $contact->id === $request->contact_id) {
            $vars['contact_name'] = $contact->__toString();
        }

        $this->blocks = [
            new Block('subunits/breadcrumbs.inc',    ['address' => $info->address]),
            new Block('subunits/actions/addForm.inc', $vars),
            'panel-one' => [
                new Block('subunits/list.inc', [
                    'address'        => $info->address,
                    'subunits'       => $info->subunits,
                    'disableButtons' => true
                ])
            ]

        ];
    }
}
