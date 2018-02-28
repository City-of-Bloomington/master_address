<?php
/**
 * @copyright 2017-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Subunits\Views;

use Application\Block;
use Application\Template;

use Domain\Subunits\Metadata;
use Domain\Subunits\UseCases\Info\InfoResponse;
use Domain\Subunits\UseCases\Correct\CorrectRequest;

class CorrectView extends Template
{
    public function __construct(CorrectRequest $request, InfoResponse $info, Metadata $metadata)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('two-column', $format);
        $this->vars['title'] = $this->_('correct');

        $vars = ['types' => $metadata->types()];
        foreach ($request as $k=>$v) { $vars[$k] = parent::escape($v); }
        $this->blocks[] = new Block('subunits/actions/correctForm.inc', $vars);

        $this->blocks[]              = new Block('logs/statusLog.inc',      ['statuses'  => $info->statusLog]);
        $this->blocks[]              = new Block('logs/changeLog.inc',      ['changes'   => $info->changeLog]);
        $this->blocks['panel-one'][] = new Block('locations/locations.inc', ['locations' => $info->locations]);
    }
}
