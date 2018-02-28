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

        if (isset($_SESSION['USER'])) {
            $actions   = ['verify', 'correct'];
            $actions[] = $info->address->status == Log::STATUS_RETIRED
                    ? 'unretire'
                    : 'retire';
        }

        $this->blocks[] = new Block('addresses/info.inc', [
            'address' => $info->address,
            'title'   => $this->vars['title'],
            'actions' => $actions
        ]);

        $this->blocks[]              = new Block('logs/statusLog.inc',  ['statuses'  => $info->statusLog]);
        $this->blocks[]              = new Block('logs/changeLog.inc', ['changes'   => $info->changeLog]);
        $this->blocks['panel-one'][] = new Block('locations/locations.inc',  ['locations' => $info->locations]);
        $this->blocks['panel-one'][] = new Block('subunits/list.inc',        ['address'   => $info->address, 'subunits' => $info->subunits]);
    }
}
