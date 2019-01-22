<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param int      $this->name_id       Street name ID
 * @param int      $this->type_id       Designation type ID
 * @param int      $this->rank
 * @param DateTime $this->start_date
 * @param string   $this->name          Display string for the current street name
 * @param array    $this->types         Designation type options
 */
declare (strict_types=1);
use Application\Url;

$h = $this->template->getHelper('field');

$options = [];
foreach ($this->types as $t) { $options[] = ['value'=>$t['id'], 'label'=>parent::escape($t['name'])]; }
echo $h->field([
    'name'     => 'type_id',
    'id'       => 'type_id',
    'label'    => $this->_('type'),
    'value'    => $this->type_id,
    'type'     => 'select',
    'options'  => $options,
    'required' => true
]);

echo $h->field([
    'name'     => 'name_id',
    'id'       => 'name_id',
    'label'    => $this->_('name'),
    'value'    => $this->name_id,
    'display'  => $this->name,
    'type'     => 'chooser',
    'chooser'  => 'streetName',
    'required' => true
]);

echo $h->field([
    'name'    => 'rank',
    'id'      => 'rank',
    'label'   => $this->_('rank'),
    'value'   => $this->rank,
    'type'    => 'number'
]);

echo $h->field([
    'name'     => 'start_date',
    'id'       => 'start_date',
    'label'    => 'startDate',
    'type'     => 'date',
    'value'    => $this->start_date,
    'required' => true
]);
