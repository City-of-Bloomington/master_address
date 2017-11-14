<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Streets\Views\Actions;

use Blossom\Classes\Block;
use Blossom\Classes\Template;

class VerifyView extends Template
{
    public function __construct(array $vars)
    {
        parent::__construct('two-column', 'html', $vars);

        $this->blocks[] = new Block('streets/actions/verifyForm.inc',      ['request' => $this->request]);
        $this->blocks['panel-one'][] = new Block('streets/streetNames.inc', ['street' => $this->request->street]);
        $this->blocks['panel-one'][] = new Block('addresses/list.inc',   ['addresses' => $this->request->street->getAddresses()]);
    }
}
