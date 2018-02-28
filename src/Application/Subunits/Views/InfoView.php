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
use Domain\Logs\Metadata as Log;

class InfoView extends Template
{
    public function __construct(InfoResponse $info)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('two-column', $format);

        if ($info->errors) { $_SESSION['errorMessages'] = $info->errors; }

        $actions = ['verify'];
        if ($info->subunit->status == Log::STATUS_CURRENT) {
            $actions[] = 'correct';
            $actions[] = 'retire';
        }
        else {
            $actions[] = 'unretire';
        }

        $this->blocks[] = new Block('subunits/info.inc', [
            'subunit' => $info->subunit,
            'title'   => parent::escape($info->subunit),
            'actions' => $actions
        ]);

        $this->blocks[]              = new Block('logs/statusLog.inc',  ['statuses'  => $info->statusLog]);
        $this->blocks[]              = new Block('logs/changeLog.inc', ['changes'   => $info->changeLog]);
        $this->blocks['panel-one'][] = new Block('locations/locations.inc',  ['locations' => $info->locations]);
    }
}
