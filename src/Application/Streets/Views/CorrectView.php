<?php
/**
 * @copyright 2017-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Streets\Views;

use Application\Block;
use Application\Template;

use Domain\Addresses\UseCases\Search\SearchResponse;
use Domain\Streets\UseCases\Info\InfoResponse;
use Domain\Streets\UseCases\Correct\CorrectRequest;
use Domain\Streets\Metadata;
use Domain\People\Entities\Person;

class CorrectView extends Template
{
    public function __construct(CorrectRequest $request,
                                InfoResponse   $info,
                                Metadata       $metadata,
                                SearchResponse $addressSearch,
                                ?Person        $contact=null)
    {
        parent::__construct('two-column', 'html');
        $this->vars['title'] = $this->_('correct');

        $vars = ['towns' => $metadata->towns()];
        foreach ($request as $k=>$v) { $vars[$k] = parent::escape($v); }
        if ($contact && $contact->id === $request->contact_id) {
            $vars['contact_name'] = $contact->__toString();
        }
        $this->blocks[] = new Block('streets/actions/correctForm.inc', $vars);

        $this->blocks[] = new Block('logs/changeLog.inc',            ['changes'      => $info->changeLog]);
        $this->blocks[] = new Block('streets/designations/list.inc', ['designations' => $info->designations]);
        $this->blocks['panel-one'][] = new Block('addresses/list.inc', ['addresses' => $addressSearch->addresses]);
    }
}
