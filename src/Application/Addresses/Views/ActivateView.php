<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Addresses\Views;

use Application\Block;
use Application\Template;

use Domain\Addresses\UseCases\Activate\Request;
use Domain\Addresses\UseCases\Info\InfoResponse;

class ActivateView extends Template
{
    public function __construct(Request      $request,
                                InfoResponse $info,
                                ?Person      $contact=null)
    {
        parent::__construct('two-column', 'html');
        $this->vars['title'] = $this->_('activate');

        $vars = [
            'address_id'   => $request->address_id,
            'location_id'  => $request->location_id,
            'address'      => $info->address,
            'contact_id'   => $contact ? $contact->id           : null,
            'contact_name' => $contact ? $contact->__toString() : null,
            'change_notes' => parent::escape($request->change_notes)
        ];

        $this->blocks = [
            new Block('addresses/actions/activateForm.inc', $vars),
            'panel-one' => [
                new Block('locations/locations.inc', ['locations' => $info->locations, 'disableButtons' => true])
            ]
        ];
    }
}
