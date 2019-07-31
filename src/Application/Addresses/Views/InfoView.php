<?php
/**
 * @copyright 2017-2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Addresses\Views;

use Application\Block;
use Application\Template;

use Domain\Addresses\UseCases\Info\InfoResponse;

class InfoView extends Template
{
    public function __construct(InfoResponse $info)
    {
        global $DEFAULTS;

        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        $template = $format == 'html' ? 'two-column' : 'default';
        parent::__construct($template, $format);

        $this->vars['title'] = $info->address->__toString();

        if ($format == 'html') {
            if ($info->errors) { $_SESSION['errorMessages'] = $info->errors; }

            $this->blocks = [
                new Block('addresses/breadcrumbs.inc',   ['address'   => $info->address]),
                new Block('addresses/info.inc',          ['address'   => $info->address,
                                                          'title'     => $this->vars['title']])
            ];
            if ($info->statusLog) { $this->blocks[] = new Block('logs/statusLog.inc', ['statuses'  => $info->statusLog]); }
            if ($info->changeLog) {
                $this->blocks[] = new Block('logs/changeLog.inc', ['entries'=>$info->changeLog->entries,
                                                                   'total'  =>$info->changeLog->total]);
            }
            if ($info->locations) {
                $this->blocks['panel-one'][] = new Block('addresses/locations.inc', [
                    'locations'          => $info->locations,
                    'userCanActivate'    => parent::isAllowed('addresses', 'activate'),
                    'sanitationEditable' => $info->address->jurisdiction_name == $DEFAULTS['city']
                                            && parent::isAllowed('sanitation', 'update')
                ]);
            }
            if ($info->purposes) { $this->blocks['panel-one'][] = new Block('addresses/purposes.inc', ['purposes' => $info->purposes]); }
            if ($info->subunits) { $this->blocks['panel-one'][] = new Block('subunits/list.inc',      ['subunits' => $info->subunits]); }
        }
        else {
            $this->blocks = [
                new Block('addresses/info.inc', ['info' => $info])
            ];
        }
    }
}
