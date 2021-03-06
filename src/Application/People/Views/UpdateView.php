<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
namespace Application\People\Views;

use Application\Block;
use Application\Template;

use Domain\People\Entities\Person;
use Domain\People\UseCases\Update\UpdateResponse;

class UpdateView extends Template
{
    public function __construct(Person $person, ?UpdateResponse $response)
    {
        parent::__construct('default', 'html');

        if ($response && $response->errors) {
            $_SESSION['errorMessages'] = $response->errors;
        }

        $this->vars['title'] = $person->id ? $this->_('person_edit') : $this->_('person_add');

        $this->blocks[] = new Block('people/updateForm.inc', [
            'person' => $person,
            'title'  => $this->vars['title']
        ]);
    }
}
