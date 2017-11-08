<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Views\Addresses;

use Blossom\Classes\Block;
use Blossom\Classes\Template;

class InfoView extends Template
{
    public function __construct(array $vars)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('two-column', $format, $vars);


        $this->blocks[] = new Block('addresses/info.inc',                   ['address'   => $this->address]);
        $this->blocks[] = new Block('changeLogs/changeLog.inc',             ['changes'   => $this->address->getChangeLog()]);
        $this->blocks['panel-one'][] = new Block('locations/locations.inc', ['locations' => $this->address->getLocations()]);
        $this->blocks['panel-one'][] = new Block('subunits/list.inc',       ['address'   => $this->address, 'subunits' => $this->address->getSubunits()]);
    }
}
