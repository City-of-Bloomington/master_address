<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param int    $this->name_id       Street name ID
 * @param int    $this->type_id       Designation type ID
 * @param int    $this->rank
 * @param string $this->name          Display string for the current street name
 * @param array  $this->types         Designation type options
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
    'url'      => new Url(parent::generateUrl('streetNames.index')),
    'required' => true
]);

echo $h->field([
    'name'    => 'rank',
    'id'      => 'rank',
    'label'   => $this->_('rank'),
    'value'   => $this->rank,
    'type'    => 'number'
]);
