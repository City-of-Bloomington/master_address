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
 * @param string $this->mailable;
 * @param string $this->occupiable;
 * @param string $this->group_quarter
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

$this->_include('locations/partials/booleanFields.inc');
