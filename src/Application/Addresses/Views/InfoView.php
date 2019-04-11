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

        if ($info->errors) { $_SESSION['errorMessages'] = $info->errors; }

        if ($format == 'html') {
            $sanitation_editable = $info->address->jurisdiction_name == $DEFAULTS['city'];

            $this->blocks = [
                new Block('addresses/breadcrumbs.inc',   ['address'   => $info->address]),
                new Block('addresses/info.inc',          ['address'   => $info->address,
                                                          'title'     => $this->vars['title'],
                                                          'actions'   => ['verify', 'changeStatus', 'correct', 'readdress']]),

                new Block('logs/statusLog.inc',          ['statuses'  => $info->statusLog]),
                new Block('logs/changeLog.inc',          ['entries'   => $info->changeLog->entries,
                                                          'total'     => $info->changeLog->total]),
                'panel-one' => [
                    new Block('locations/locations.inc', ['locations' => $info->locations, 'disableButtons' => !$sanitation_editable]),
                    new Block('addresses/purposes.inc',  ['purposes'  => $info->purposes ]),
                    new Block('subunits/list.inc',       ['address'   => $info->address, 'subunits' => $info->subunits])
                ]
            ];
        }
        else {
            $this->blocks = [
                new Block('addresses/info.inc', ['info' => $info])
            ];
        }
    }
}
