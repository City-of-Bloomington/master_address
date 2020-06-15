<?php
/**
 * @copyright 2019-2020 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Subunits\Views;

use Application\Block;
use Application\Template;

use Domain\Locations\Metadata as Location;
use Domain\Subunits\UseCases\Info\InfoResponse;
use Domain\Subunits\UseCases\Update\Request;
use Domain\People\Entities\Person;

class UpdateView extends Template
{
    public function __construct(Request       $request,
                                Location      $location,
                                InfoResponse  $info,
                                ?Person       $contact=null)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('two-column', $format);

        $vars = [
            'subunit'       => $info->subunit,
            'contact_name'  => $contact ? $contact->__toString() : null,
            'locationTypes' => $location->types()
        ];
        foreach ($request as $k=>$v) { $vars[$k] = is_string($v) ? parent::escape($v) : $v; }

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
