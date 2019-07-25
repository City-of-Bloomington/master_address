<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Streets\Views;

use Application\Block;
use Application\Template;

use Domain\Streets\UseCases\Info\InfoResponse;

class ReorderView extends Template
{
    public function __construct(InfoResponse $info)
    {
        parent::__construct('default', 'html');
        $this->vars['title'] = $this->_('reorder');

        $vars = [
            'street_id'    => $info->street->id,
            'designations' => $info->designations
        ];

        $this->blocks = [
            new Block('streets/info.inc', ['street' => $info->street, 'disableButtons' => true]),
            new Block('streets/designations/reorderForm.inc', $vars)
        ];
    }
}
