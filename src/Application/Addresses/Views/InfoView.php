<?php
/**
 * @copyright 2017-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Addresses\Views;

use Application\Block;
use Application\Template;

use Domain\Addresses\Entities\Address;
use Domain\Addresses\UseCases\Info\InfoResponse;
use Domain\Logs\Metadata as Log;

class InfoView extends Template
{
    public function __construct(InfoResponse $info)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('two-column', $format);

        $this->vars['title'] = $info->address->__toString();

        if ($info->errors) { $_SESSION['errorMessages'] = $info->errors; }

        $actions = null;
        if (isset($_SESSION['USER'])) {
            $actions = [
                Log::ACTION_VERIFY,
                Log::ACTION_CORRECT,
                ($info->address->status == Log::STATUS_RETIRED) ? Log::ACTION_UNRETIRE : Log::ACTION_RETIRE
            ];
        }

        $this->blocks = [
            new Block('addresses/breadcrumbs.inc', ['address'  => $info->address]),

            new Block('addresses/info.inc',        ['address'  => $info->address,
                                                    'title'    => $this->vars['title'],
                                                    'actions'  => $actions]),

            new Block('logs/statusLog.inc',        ['statuses' => $info->statusLog]),

            new Block('logs/changeLog.inc',        ['entries'      => $info->changeLog->entries,
                                                    'total'        => $info->changeLog->total]),
            'panel-one' => [
                new Block('locations/locations.inc', ['locations' => $info->locations]),
                new Block('subunits/list.inc',       ['address'   => $info->address, 'subunits' => $info->subunits])
            ]
        ];
    }
}
