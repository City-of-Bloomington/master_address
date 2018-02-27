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
use Domain\Subunits\UseCases\Verify\VerifyRequest;

class VerifyView extends Template
{
    public function __construct(VerifyRequest $request, InfoResponse $info)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('two-column', $format);

        if ($info->errors) { $_SESSION['errorMessages'] = $info->errors; }

        $this->blocks[] = new Block('generic/verifyForm.inc', [
            'id'         => $request->subunit_id,
            'notes'      => parent::escape($request->notes),
            'help'       => parent::escape(sprintf($this->_('verify_statement', 'messages'), $_SESSION['USER']->getFullname())),
            'return_url' => parent::generateUri('subunits.view', ['id'=>$request->subunit_id])
        ]);

        $this->blocks[]              = new Block('subunits/info.inc',        ['subunit'   => $info->subunit  ]);
        $this->blocks[]              = new Block('addresses/statusLog.inc',  ['statuses'  => $info->statusLog]);
        $this->blocks[]              = new Block('changeLogs/changeLog.inc', ['changes'   => $info->changeLog]);
        $this->blocks['panel-one'][] = new Block('locations/locations.inc',  ['locations' => $info->locations]);
    }
}
