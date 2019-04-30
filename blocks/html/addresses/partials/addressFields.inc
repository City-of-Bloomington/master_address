<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 *---------------------------------------------------------
 * Information to populate select dropdown options
 *---------------------------------------------------------
 * @param array  $this->cities           Cities to choose from
 * @param array  $this->jurisdictions    Jurisdictions to choose from
 * @param array  $this->quarterSections  Quarter Sections to choose from
 * @param array  $this->sections         Sections to choose from
 * @param array  $this->statuses         Address statuses to choose from
 * @param array  $this->townships        Townships to choose from
 * @param array  $this->types            Address types to choose from
 * @param array  $this->zipCodes         Zip codes to choose from
 *
 *---------------------------------------------------------
 * Address table fields
 *---------------------------------------------------------
 * @param string $this->status
 * @param string $this->street_number_prefix
 * @param string $this->street_number
 * @param string $this->street_number_suffix
 * @param string $this->street_id
 * @param string $this->street_name           The displayable name for the chosen street_id
 * @param string $this->address2
 * @param string $this->address_type
 * @param string $this->jurisdiction_id
 * @param string $this->township_id
 * @param string $this->subdivision_id
 * @param string $this->plat_id
 * @param string $this->section
 * @param string $this->quarter_section
 * @param string $this->plat_lot_number
 * @param string $this->city
 * @param string $this->state
 * @param string $this->zip
 * @param string $this->zipplus4
 * @param string $this->notes
 */
declare (strict_types=1);
use Application\Url;

$h = $this->template->getHelper('field');

$options = [['value'=>'']];
foreach ($this->types as $c) { $options[] = ['value'=>$c]; }
echo $h->field([
    'name'     => 'address_type',
    'id'       => 'address_type',
    'label'    => $this->_('type'),
    'value'    => $this->address_type,
    'type'     => 'select',
    'options'  => $options,
    'required' => true
]);

$this->_include('addresses/partials/streetFields.inc');

echo $h->field([
    'name'  => 'address2',
    'id'    => 'address2',
    'label' => $this->_('address2'),
    'value' => $this->address2
]);

$options = [['value'=>'']];
foreach ($this->zipCodes as $z) { $options[] = ['value' => $z]; }
echo $h->field([
    'name'     => 'zip',
    'id'       => 'zip',
    'label'    => $this->_('zip'),
    'value'    => $this->zip,
    'type'     => 'select',
    'options'  => $options,
    'required' => true
]);

echo $h->field([
    'name'     => 'zipplus4',
    'id'       => 'zipplus4',
    'label'    => $this->_('zipplus4'),
    'value'    => $this->zipplus4,
    'type'     => 'number'
]);

$options = [];
foreach ($this->cities as $c) { $options[] = ['value'=>$c]; }
echo $h->field([
    'name'     => 'city',
    'id'       => 'city',
    'label'    => $this->_('city'),
    'value'    => $this->city,
    'type'     => 'select',
    'options'  => $options,
    'required' => true
]);

$this->_include('addresses/partials/descriptiveFields.inc');
