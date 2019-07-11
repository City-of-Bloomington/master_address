<?php
/**
 * Draws the form fields for Locations
 *
 * @copyright 2009-2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU/AGPL, see LICENSE
 *
 * @param array  $this->locationTypes   Location types to choose from
 *
 * @param string $this->locationType_id;
 * @param bool   $this->mailable;
 * @param bool   $this->occupiable;
 * @param bool   $this->group_quarter
 */

$h = $this->template->getHelper('field');

$options = [];
foreach ($this->locationTypes as $t) { $options[] = ['value'=>$t['id'], 'label'=>parent::escape($t['name'])]; }
echo $h->field([
    'name'     => 'locationType_id',
    'id'       => 'locationType_id',
    'label'    => $this->_('type'),
    'type'     => 'select',
    'value'    => $this->locationType_id,
    'options'  => $options,
    'required' => true
]);

$options = [
    ['value'=>1, 'label'=>$this->_('yes')],
    ['value'=>0, 'label'=>$this->_('no' )]
];
echo $h->field([
    'name'     => 'mailable',
    'id'       => 'mailable',
    'label'    => $this->_('mailable'),
    'type'     => 'radio',
    'value'    => $this->mailable,
    'options'  => $options
]);

echo $h->field([
    'name'     => 'occupiable',
    'id'       => 'occupiable',
    'label'    => $this->_('occupiable'),
    'type'     => 'radio',
    'value'    => $this->occupiable,
    'options'  => $options
]);

echo $h->field([
    'name'     => 'group_quarter',
    'id'       => 'group_quarter',
    'label'    => $this->_('group_quarter'),
    'type'     => 'radio',
    'value'    => $this->group_quarter,
    'options'  => $options
]);
