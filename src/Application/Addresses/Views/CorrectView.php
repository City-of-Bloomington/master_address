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
use Domain\People\Entities\Person;
use Domain\Streets\Entities\Street;

class CorrectView extends Template
{
    public function __construct(CorrectRequest $request,
                                InfoResponse   $info,
                                ?Street        $street=null,
                                ?Person        $contact=null)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('two-column', $format);
        $this->vars['title'] = $this->_('correct');

        $vars = [
            'street_id'    => $street  ? $street->id            : null,
            'street_name'  => $street  ? $street->__toString()  : null,
            'contact_id'   => $contact ? $contact->id           : null,
            'contact_name' => $contact ? $contact->__toString() : null,
            'change_notes' => parent::escape($request->change_notes)
        ];
        foreach ($request as $k=>$v) { $vars[$k] = parent::escape($v); }
        $this->blocks[] = new Block('addresses/actions/correctForm.inc', $vars);

        $this->blocks[]              = new Block('logs/statusLog.inc',  ['statuses'  => $info->statusLog]);
        $this->blocks[]              = new Block('logs/changeLog.inc', ['changes'   => $info->changeLog]);
        $this->blocks['panel-one'][] = new Block('locations/locations.inc',  ['locations' => $info->locations]);
        $this->blocks['panel-one'][] = new Block('subunits/list.inc',        ['address'   => $info->address, 'subunits' => $info->subunits]);
    }
}
