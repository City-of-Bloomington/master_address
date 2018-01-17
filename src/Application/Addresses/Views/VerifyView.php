<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Addresses\Views;

use Blossom\Classes\Block;
use Blossom\Classes\Template;

class VerifyView extends Template
{
    public function __construct(array $vars)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('two-column', $format, $vars);


        $address = $this->request->address;
        $this->blocks[] = new Block('addresses/actions/verifyForm.inc',     ['request'   => $this->request]);
        $this->blocks['panel-one'][] = new Block('locations/locations.inc', ['locations' => $address->getLocations()]);
        $this->blocks['panel-one'][] = new Block('subunits/list.inc',       ['address'   => $address, 'subunits' => $address->getSubunits()]);
    }
}
