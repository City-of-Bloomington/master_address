<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
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

        $format   = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        $template = $format == 'html' ? 'two-column' : 'default';

        parent::__construct($template, $format);

        if ($info->errors) { $_SESSION['errorMessages'] = $info->errors; }

        if ($this->outputFormat == 'html') {
            $this->blocks = [
                new Block('subunits/breadcrumbs.inc', ['address'  => $info->address]),
                new Block('subunits/info.inc',        ['subunit'  => $info->subunit,
                                                       'title'    => parent::escape($info->subunit)]),
                new Block('logs/statusLog.inc',       ['statuses' => $info->statusLog]),
                new Block('logs/changeLog.inc',       ['entries'  => $info->changeLog->entries,
                                                       'total'    => $info->changeLog->total]),
                'panel-one' => [
                    new Block('subunits/locations.inc', [
                        'locations'          => $info->locations,
                        'userCanActivate'    => parent::isAllowed('subunits', 'activate'),
                        'sanitationEditable' => self::sanitationEditable($info)
                    ])
                ]
            ];
        }
        else {
            $this->blocks = [
                new Block('subunits/info.inc', ['info'=>$info])
            ];
        }
    }

    private static function sanitationEditable(InfoResponse $info): bool
    {
        global $DEFAULTS;

        return $info->address->jurisdiction_name == $DEFAULTS['city']
               && parent::isAllowed('sanitation', 'update');
    }
}
