<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Addresses\Views;

use Application\Block;
use Application\Template;

use Domain\Addresses\Metadata as AddressMetadata;
use Domain\Locations\Metadata as LocationMetadata;

use Domain\Addresses\UseCases\Add\Add;
use Domain\Addresses\UseCases\Add\AddRequest;
use Domain\People\Entities\Person;
use Domain\Locations\Entities\Location;
use Domain\Streets\Entities\Street;

class AddView extends Template
{
    public function __construct(AddRequest       $request,
                                AddressMetadata  $addressMetadata,
                                LocationMetadata $locationMetadata,
                                string           $cancel_url,
                                ?Street    $street     = null,
                                ?Person    $contact    = null,
                                ?Location  $location   = null)
    {
        parent::__construct('default', 'html');
        $this->vars['title'] = $this->_('address_add');

        $vars = [
            'cancel_url'      => $cancel_url,
            'street_name'     => $street     ? $street ->__toString() : null,
            'contact_name'    => $contact    ? $contact->__toString() : null,
            'location'        => $location   ? $location   : null,
            'cities'          => $addressMetadata->cities(),
            'jurisdictions'   => $addressMetadata->jurisdictions(),
            'quarterSections' => $addressMetadata->quarterSections(),
            'sections'        => $addressMetadata->sections(),
            'statuses'        => $addressMetadata->statuses(),
            'types'           => $addressMetadata->types(),
            'townships'       => $addressMetadata->townships(),
            'zipCodes'        => $addressMetadata->zipCodes(),
            'locationTypes'   => $locationMetadata->types(),
            'validActions'    => Add::$validActions
        ];
        foreach ($request as $k=>$v) { $vars[$k] = parent::escape($v); }

        $this->blocks = [
            new Block('addresses/actions/addForm.inc', $vars)
        ];
    }
}
