<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 *---------------------------------------------------------
 * Information to populate select dropdown options
 *---------------------------------------------------------
 * @param array  $this->jurisdictions    Jurisdictions to choose from
 * @param array  $this->quarterSections  Quarter Sections to choose from
 * @param array  $this->sections         Sections to choose from
 * @param array  $this->townships        Townships to choose from
 *---------------------------------------------------------
 * Address table fields
 *---------------------------------------------------------
 * @param string $this->jurisdiction_id
 * @param string $this->township_id
 * @param string $this->subdivision_id
 * @param string $this->plat_id
 * @param string $this->section
 * @param string $this->quarter_section
 * @param string $this->plat_lot_number
 * @param string $this->notes
 */
declare (strict_types=1);

$h = $this->template->getHelper('field');

$options = [];
foreach ($this->jurisdictions as $c) { $options[] = ['value'=>$c['id'], 'label'=>parent::escape($c['name'])]; }
echo $h->field([
    'name'     => 'jurisdiction_id',
    'id'       => 'jurisdiction_id',
    'label'    => $this->_('jurisdiction'),
    'value'    => $this->jurisdiction_id,
    'type'     => 'select',
    'options'  => $options,
    'required' => true
]);

#plat_id
echo $h->field([
    'name'     => 'plat_id',
    'id'       => 'plat_id',
    'label'    => $this->_('plat'),
    'type'     => 'chooser',
    'chooser'  => 'plat',
    'value'    => $this->plat_id,
    'display'  => $this->plat_id
]);

#plat_lot_number
echo $h->field([
    'name'     => 'plat_lot_number',
    'id'       => 'plat_lot_number',
    'label'    => $this->_('plat_lot_number'),
    'value'    => $this->plat_lot_number
]);

$options = [['value'=>'']];
foreach ($this->townships as $c) { $options[] = ['value'=>$c['id'], 'label'=>parent::escape($c['name'])]; }
echo $h->field([
    'name'     => 'township_id',
    'id'       => 'township_id',
    'label'    => $this->_('township'),
    'value'    => $this->township_id,
    'type'     => 'select',
    'options'  => $options,
    'required' => true
]);


$options = [['value'=>'']];
foreach ($this->sections as $c) { $options[] = ['value'=>$c]; }
echo $h->field([
    'name'     => 'section',
    'id'       => 'section',
    'label'    => $this->_('section'),
    'value'    => $this->section,
    'type'     => 'select',
    'options'  => $options
]);

$options = [['value'=>'']];
foreach ($this->quarterSections as $c) { $options[] = ['value'=>$c]; }
echo $h->field([
    'name'     => 'quarter_section',
    'id'       => 'quarter_section',
    'label'    => $this->_('quarter_section'),
    'value'    => $this->quarter_section,
    'type'     => 'select',
    'options'  => $options
]);

echo $h->field([
    'name'     => 'notes',
    'id'       => 'notes',
    'label'    => $this->_('address_notes'),
    'value'    => $this->notes,
    'type'     => 'textarea'
]);
