<?php
/**
 * @copyright 2017-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Streets\Views;

use Application\Block;
use Application\Template;

use Domain\Streets\UseCases\Info\InfoResponse;
use Domain\Addresses\UseCases\Search\SearchResponse;

class InfoView extends Template
{
    public function __construct(InfoResponse $info, SearchResponse $search)
    {
        $format   = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        $template = $format == 'html' ? 'two-column' : 'default';
        parent::__construct($template, $format);

        if ($info->errors) { $_SESSION['errorMessages'] = $info->errors; }

        $this->vars['title'] = parent::escape($info->street->__toString());

        if ($format == 'html') {
            $this->blocks = [
                new Block('streets/info.inc',              ['street'       => $info->street]),
                new Block('logs/changeLog.inc',            ['entries'      => $info->changeLog->entries,
                                                            'total'        => $info->changeLog->total   ]),
                new Block('streets/designations/list.inc', ['designations' => $info->designations,
                                                            'street_id'    => $info->street->id]),
                'panel-one' => [
                    new Block('streets/addresses.inc',     ['street_id'    => $info->street->id,
                                                            'addresses'    => $search->addresses])
                ]
            ];
        }
        else {
            $this->blocks = [
                new Block('streets/info.inc', ['info'=>$info])
            ];
        }
    }
}
