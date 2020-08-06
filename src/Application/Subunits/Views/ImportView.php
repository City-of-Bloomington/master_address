<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Subunits\Views;

use Application\Block;
use Application\Template;

use Domain\Addresses\UseCases\Info\InfoResponse;
use Domain\People\Entities\Person;
use Domain\Subunits\UseCases\Import\Request;

class ImportView extends Template
{
    public function __construct(Request      $request,
                                InfoResponse $info)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('two-column', $format);

        $vars = [];
        foreach ($request as $k=>$v) { $vars[$k] = parent::escape($v); }

        $this->blocks = [
            new Block('subunits/breadcrumbs.inc',    ['address' => $info->address]),
            new Block('subunits/actions/importForm.inc', $vars),
            'panel-one' => [
                new Block('subunits/list.inc', [
                    'address'        => $info->address,
                    'subunits'       => $info->subunits,
                    'disableButtons' => true
                ])
            ]
        ];

    }

}
