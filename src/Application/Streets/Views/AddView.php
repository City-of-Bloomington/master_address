<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Streets\Views;

use Application\Block;
use Application\Template;

use Domain\Logs\Metadata as Log;
use Domain\Streets\Metadata;
use Domain\Streets\Entities\Name;
use Domain\Streets\UseCases\Add\AddRequest;
use Domain\People\Entities\Person;

class AddView extends Template
{
    public function __construct(AddRequest $request,
                                Metadata   $metadata,
                                ?Name      $name   =null,
                                ?Person    $contact=null)
    {
        parent::__construct('default', 'html');
        $this->vars['title'] = $this->_('street_add');

        $vars = [
            'title'    => $this->vars['title'],
            'towns'    => $metadata->towns(),
            'statuses' => [Log::STATUS_CURRENT, Log::STATUS_PROPOSED],
            'types'    => $metadata->designationTypes()
        ];
        foreach ($request as $k=>$v) { $vars[$k] = parent::escape($v); }

        // Display strings for *_id fields
        if ($name)    { $vars['name'        ] = $name   ->__toString(); }
        if ($contact) { $vars['contact_name'] = $contact->__toString(); }

        $this->blocks[] = new Block('streets/actions/addForm.inc', $vars);
    }
}
