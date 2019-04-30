<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param bool $this->mailable;
 * @param bool $this->occupiable;
 * @param bool $this->group_quarter
 */
declare (strict_types=1);

$h = $this->template->getHelper('Field');

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
