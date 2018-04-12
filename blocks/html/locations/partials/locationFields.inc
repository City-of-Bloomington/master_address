<?php
/**
 * Draws the form fields for Locations
 *
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param string $this->locationType_id;
 * @param string $this->mailable;
 * @param string $this->occupiable;
 * @param string $this->trash_day;
 * @param string $this->recycle_week;
 * @param string $this->locationStatus;
 *
 * @param array  $this->locationTypes    Location types to populate dropdown
 * @param array  $this->statuses         Statuses to populate dropdown
 */
use Domain\Locations\Metadata as Location;

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

$options = [['value'=>'']];
foreach (Location::$trash_days as $d) { $options[] = ['value'=>$d, 'label'=>$this->_($d)]; }
echo $h->field([
    'name'     => 'trash_day',
    'id'       => 'trash_day',
    'label'    => $this->_('trash_day'),
    'type'     => 'select',
    'value'    => $this->trash_day,
    'options'  => $options
]);

$options = [['value'=>'']];
foreach (Location::$recycle_weeks as $w) { $options[] = ['value'=>$w]; }
echo $h->field([
    'name'     => 'recycle_week',
    'id'       => 'recycle_week',
    'label'    => $this->_('recycle_week'),
    'type'     => 'select',
    'value'    => $this->recycle_week,
    'options'  => $options
]);

$options = [];
foreach ($this->statuses as $s) { $options[] = ['value'=>$s]; }
echo $h->field([
    'name'     => 'locationStatus',
    'id'       => 'locationStatus',
    'label'    => $this->_('locationStatus'),
    'type'     => 'select',
    'value'    => $this->locationStatus,
    'options'  => $options
]);
