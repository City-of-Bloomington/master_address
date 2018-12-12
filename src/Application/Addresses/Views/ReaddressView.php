<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Addresses\Views;

use Application\Block;
use Application\Template;

use Domain\Addresses\Metadata as AddressMetadata;
use Domain\Locations\Metadata as LocationMetadata;

use Domain\Addresses\UseCases\Info\InfoResponse;
use Domain\Addresses\UseCases\Readdress\ReaddressRequest;

use Domain\Streets\Entities\Street;
use Domain\People\Entities\Person;

class ReaddressView extends Template
{
    public function __construct(ReaddressRequest $request,
                                InfoResponse     $info,
                                AddressMetadata  $addressMetadata,
                                LocationMetadata $locationMetadata,
                                ?Street    $street     = null,
                                ?Person    $contact    = null)
    {
        parent::__construct('default', 'html');
        $this->vars['title'] = $this->_('address_add');

        $vars = [
            'subunits'        => $info->subunits,
            'street_name'     => $street     ? $street ->__toString() : null,
            'contact_name'    => $contact    ? $contact->__toString() : null,
            'cities'          => $addressMetadata->cities(),
            'jurisdictions'   => $addressMetadata->jurisdictions(),
            'quarterSections' => $addressMetadata->quarterSections(),
            'sections'        => $addressMetadata->sections(),
            'statuses'        => $addressMetadata->statuses(),
            'types'           => $addressMetadata->types(),
            'townships'       => $addressMetadata->townships(),
            'zipCodes'        => $addressMetadata->zipCodes(),
            'locationTypes'   => $locationMetadata->types()
        ];
        foreach ($request as $k=>$v) { $vars[$k] = parent::escape($v); }

        $this->blocks[] = new Block('addresses/actions/readdressForm.inc', $vars);
    }
}
