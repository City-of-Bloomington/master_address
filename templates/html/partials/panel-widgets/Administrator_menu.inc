<?php
/**
 * @copyright 2015-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
use Application\Models\Person;

$links  = [];
$routes = [
    'jurisdictions'   => 'jurisdiction',
    'towns'           => 'town',
    'townships'       => 'township',
    'people'          => 'person',
    'users'           => 'user',
    'addressStatuses' => 'addressStatus',
    'streetStatuses'  => 'streetStatus',
    'streetTypes'     => 'streetType',
    'streetNameTypes' => 'streetNameType',
    'subunitTypes'    => 'subunitType',
    'locationTypes'   => 'locationType',
    'purposes'        => 'purpose',
    'plats'           => 'plat',
    'subdivisions'    => 'subdivision',
    'streets'         => 'street'
];
foreach ($routes as $plural=>$singular) {
    if (Person::isAllowed($plural, 'index')) {
        $links[] = [
            'url'   => parent::generateUri("$plural.index"),
            'label' => $this->_([$singular, $plural, 2])];
    }
}

if ($links) {
    $helper = $this->getHelper('dropdown');
    echo $helper->dropdown($links, $this->_('admin'), 'Administrator_menu');
}
