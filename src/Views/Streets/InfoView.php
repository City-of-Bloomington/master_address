<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Views\Streets;

use Blossom\Classes\Block;
use Blossom\Classes\Template;

class InfoView extends Template
{
    public function __construct(array $vars)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('two-column', $format, $vars);


        $this->blocks[]            = new Block('streets/info.inc',        ['street' => $this->street]);
        $this->blocks[] = new Block('changeLogs/changeLog.inc', ['changes'=>$this->street->getChangeLog()]);
        $this->blocks['panel-one'][] = new Block('streets/streetNames.inc', ['street' => $this->street]);
        $this->blocks['panel-one'][] = new Block('addresses/list.inc', ['addresses' => $this->street->getAddresses()]);
    }
}
