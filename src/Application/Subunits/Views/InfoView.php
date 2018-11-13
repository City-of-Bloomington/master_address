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

        $actions = ($info->subunit->status == Log::STATUS_CURRENT)
                 ? ['verify', 'correct', 'retire']
                 : ['verify', 'unretire'];

        $this->blocks = [
            new Block('subunits/info.inc',   ['subunit'   => $info->subunit,
                                              'title'     => parent::escape($info->subunit),
                                              'actions'   => $actions]),
            new Block('logs/statusLog.inc',  ['statuses'  => $info->statusLog]),
            new Block('logs/changeLog.inc',  ['entries'   => $info->changeLog->entries,
                                              'total'     => $info->changeLog->total]),
            'panel-one' => [
                new Block('locations/locations.inc',  ['locations' => $info->locations])
            ]
        ];
    }
}
