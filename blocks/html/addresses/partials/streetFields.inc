<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param string $this->street_number_prefix
 * @param string $this->street_number
 * @param string $this->street_number_suffix
 * @param string $this->street_id
 * @param string $this->street_name           The displayable name for the chosen street_id
 */
declare (strict_types=1);

$h = $this->template->getHelper('field');
echo $h->field([
    'name'  => 'street_number_prefix',
    'id'    => 'street_number_prefix',
    'label' => $this->_('street_number_prefix'),
    'value' => $this->street_number_prefix
]);
echo $h->field([
    'name'     => 'street_number',
    'id'       => 'street_number',
    'label'    => $this->_('street_number'),
    'value'    => $this->street_number,
    'type'     => 'number',
    'required' => true
]);
echo $h->field([
    'name'  => 'street_number_suffix',
    'id'    => 'street_number_suffix',
    'label' => $this->_('street_number_suffix'),
    'value' => $this->street_number_suffix
]);

echo $h->field([
    'name'     => 'street_id',
    'id'       => 'street_id',
    'label'    => $this->_('street'),
    'type'     => 'chooser',
    'chooser'  => 'street',
    'value'    => $this->street_id,
    'display'  => $this->street_name,
    'required' => true
]);
