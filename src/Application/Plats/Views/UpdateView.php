<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Plats\Views;

use Application\Block;
use Application\Template;

use Domain\Plats\Metadata;
use Domain\Plats\UseCases\Update\UpdateRequest;

class UpdateView extends Template
{
    public function __construct(UpdateRequest $req, Metadata $metadata, ?array $errors=null)
    {
        parent::__construct('default', 'html');
        $this->vars['title'] = $req->id ? $this->_('plat_edit') : $this->_('plat_add');

        if ($errors) { $_SESSION['errorMessages'] = $errors; }

        $this->blocks[] = new Block('plats/updateForm.inc', [
            'plat'=>$req,
            'options' => [
                'types'     => $metadata->types(),
                'cabinets'  => $metadata->cabinets(),
                'townships' => $metadata->townships()
            ],
            'title' => $this->vars['title']
        ]);
    }
}
