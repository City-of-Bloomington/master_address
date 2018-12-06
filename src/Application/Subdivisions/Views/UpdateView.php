<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\Subdivisions\Views;

use Application\Block;
use Application\Template;

use Domain\Subdivisions\Metadata;
use Domain\Subdivisions\UseCases\Update\UpdateRequest;

class UpdateView extends Template
{
    public function __construct(UpdateRequest $req, Metadata $metadata, ?array $errors=null)
    {
        parent::__construct('default', 'html');
        $this->vars['title'] = $req->id ? $this->_('subdivision_edit') : $this->_('subdivision_add');

        if ($errors) { $_SESSION['errorMessages'] = $errors; }

        $this->blocks[] = new Block('subdivisions/updateForm.inc', [
            'subdivision'=>$req,
            'options' => [
                'phases'    => $metadata->phases(),
                'statuses'  => $metadata->statuses(),
                'townships' => $metadata->townships()
            ],
            'title' => $this->vars['title']
        ]);
    }
}
