<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Application\Streets\Views;

use Application\Block;
use Application\Template;

use Domain\Addresses\UseCases\Renumber\RenumberRequest;
use Domain\Addresses\UseCases\Search\SearchResponse;
use Domain\Streets\UseCases\Info\InfoResponse;
use Domain\People\Entities\Person;

class RenumberView extends Template
{
    public function __construct(RenumberRequest $request,
                                InfoResponse    $info,
                                SearchResponse  $addresses,
                                ?Person         $contact)
    {
        parent::__construct('default', 'html');
        if ($info->errors) { $_SESSION['errorMessages'] = $info->errors; }

        $this->vars['title'] = $this->_('renumber');

        $temp = [];
        foreach ($addresses->addresses     as $a) { $temp[$a->id        ]['current'] = $a; }
        foreach ($request->address_numbers as $n) { $temp[$n->address_id]['updated'] = $n; }
        $vars = [
            'title'     => $this->_('renumber'),
            'street_id' => $info->street->id,
            'addresses' => $temp
        ];

        $this->blocks[] = new Block('streets/info.inc', ['street'=>$info->street, 'disableButtons'=>true]);
        $this->blocks[] = new Block('streets/actions/renumberForm.inc', $vars);
    }
}
