<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param string $this->contact_id   Change log contact person
 * @param string $this->contact_name Change log contact person
 * @param string $this->change_notes
 */
declare (strict_types=1);
use Application\Url;

$helper = $this->template->getHelper('field');
echo $helper->field([
    'name'    => 'contact_id',
    'id'      => 'contact_id',
    'label'   => $this->_('contact'),
    'value'   => $this->contact_id,
    'display' => $this->contact_name,
    'type'    => 'chooser',
    'url'     => new Url(parent::generateUrl('people.index'))
]);

echo $helper->field([
    'name'  => 'change_notes',
    'id'    => 'change_notes',
    'label' => $this->_('notes'),
    'type'  => 'textarea',
    'value' => $this->change_notes
]);
