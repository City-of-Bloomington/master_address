<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param string $this->direction
 * @param string $this->name
 * @param string $this->post_direction
 * @param string $this->suffix_code_id
 * @param string $this->notes
 * @param array  $this->directions
 * @param array  $this->types
 */
declare (strict_types=1);
$h = $this->template->getHelper('field');

$options = [['value'=>'']];
foreach ($this->directions as $name=>$code) { $options[] = ['value' => $code, 'label'=>$name]; }
echo $h->field([
    'name'    => 'direction',
    'id'      => 'direction',
    'label'   => $this->_('direction'),
    'value'   => $this->direction,
    'type'    => 'select',
    'options' => $options
]);

echo $h->field([
    'name'     => 'name',
    'id'       => 'name',
    'label'    => $this->_('name'),
    'value'    => $this->name,
    'required' => true
]);

$options = [['value'=>'']];
foreach ($this->types as $t) { $options[] = ['value' => $t['id'], 'label'=>parent::escape($t['name'])]; }
echo $h->field([
    'name'    => 'suffix_code_id',
    'id'      => 'suffix_code_id',
    'label'   => $this->_('type'),
    'value'   => $this->suffix_code_id,
    'type'    => 'select',
    'options' => $options
]);

$options = [['value'=>'']];
foreach ($this->directions as $name=>$code) { $options[] = ['value' => $code, 'label'=>$name]; }
echo $h->field([
    'name'    => 'post_direction',
    'id'      => 'post_direction',
    'label'   => $this->_('post_direction'),
    'value'   => $this->post_direction,
    'type'    => 'select',
    'options' => $options
]);

echo $h->field([
    'name'     => 'notes',
    'id'       => 'notes',
    'label'    => $this->_('notes'),
    'value'    => $this->notes,
    'type'     => 'textarea'
]);
