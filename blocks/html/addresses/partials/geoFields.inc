<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU/AGPL, see LICENSE
 * @param string $this->state_plane_x
 * @param string $this->state_plane_y
 * @param string $this->latitude
 * @param string $this->longitude
 * @param string $this->usng
 */
declare (strict_types=1);
$h = $this->template->getHelper('field');

echo $h->field([
    'name'  => 'state_plane_x',
    'id'    => 'state_plane_x',
    'label' => $this->_('state_plane_x'),
    'value' => $this->state_plane_x,
    'type'  => 'number'
]);
echo $h->field([
    'name'  => 'state_plane_y',
    'id'    => 'state_plane_y',
    'label' => $this->_('state_plane_y'),
    'value' => $this->state_plane_y,
    'type'  => 'number'
]);
echo $h->field([
    'name'  => 'latitude',
    'id'    => 'latitude',
    'label' => $this->_('latitude'),
    'value' => $this->latitude,
    'type'  => 'number',
    'attr'  => [
        'step'        =>  '0.00000001',
        'placeholder' => '39.00000000'
    ]
]);
echo $h->field([
    'name'  => 'longitude',
    'id'    => 'longitude',
    'label' => $this->_('longitude'),
    'value' => $this->longitude,
    'type'  => 'number',
    'attr'  => [
        'step'        =>   '0.00000001',
        'placeholder' => '-86.00000000'
    ]
]);
echo $h->field([
    'name'  => 'usng',
    'id'    => 'usng',
    'label' => $this->_('usng'),
    'value' => $this->usng
]);
