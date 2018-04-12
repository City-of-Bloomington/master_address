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
use Domain\Logs\Metadata as Log;
use Domain\People\Entities\Person;
use Domain\Subunits\Metadata;
use Domain\Subunits\UseCases\Add\AddRequest;


class AddView extends Template
{
    public function __construct(AddRequest   $request,
                                InfoResponse $info,
                                Metadata     $metadata,
                                ?Person      $contact=null)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('two-column', $format);
        $this->vars['title'] = $this->_(Log::ACTION_ADD);

        $vars = [
             'subunitTypes' => $metadata->types(),
            'locationTypes' => $metadata->locationTypes(),
            'statuses'      => Log::$statuses
        ];
        foreach ($request as $k=>$v) { $vars[$k] = parent::escape($v); };
        if ($contact && $contact->id === $request->contact_id) {
            $vars['contact_name'] = $contact->__toString();
        }

        $this->blocks[] = new Block('subunits/actions/addForm.inc', $vars);

        $this->blocks[]              = new Block('logs/statusLog.inc',      ['statuses'  => $info->statusLog]);
        $this->blocks[]              = new Block('logs/changeLog.inc',      ['changes'   => $info->changeLog]);
        $this->blocks['panel-one'][] = new Block('locations/locations.inc', ['locations' => $info->locations]);
        $this->blocks['panel-one'][] = new Block('subunits/list.inc',       ['address'   => $info->address, 'subunits' => $info->subunits]);
    }
}
