<?php
/**
 * @copyright 2017-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Addresses\Views;

use Application\Block;
use Application\Template;

use Domain\Addresses\UseCases\Info\InfoResponse;
use Domain\Addresses\UseCases\Correct\CorrectRequest;
use Domain\Streets\Entities\Street;

class CorrectView extends Template
{
    public function __construct(CorrectRequest $request, InfoResponse $info, ?Street $street=null)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('two-column', $format);
        $this->vars['title'] = $this->_('correct');

        $vars = [];
        foreach ($request as $k=>$v) { $vars[$k] = parent::escape($v); }
        if ($street) {
            $vars['street_id'  ] = $street->id;
            $vars['street_name'] = parent::escape(implode(' ', [
                $street->direction,
                $street->name,
                $street->post_direction,
                $street->suffix_code
            ]));
        }
        $this->blocks[] = new Block('addresses/actions/correctForm.inc', $vars);

        $this->blocks[]              = new Block('logs/statusLog.inc',  ['statuses'  => $info->statusLog]);
        $this->blocks[]              = new Block('logs/changeLog.inc', ['changes'   => $info->changeLog]);
        $this->blocks['panel-one'][] = new Block('locations/locations.inc',  ['locations' => $info->locations]);
        $this->blocks['panel-one'][] = new Block('subunits/list.inc',        ['address'   => $info->address, 'subunits' => $info->subunits]);
    }
}
