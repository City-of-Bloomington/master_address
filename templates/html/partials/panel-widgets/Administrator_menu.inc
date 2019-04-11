<?php
/**
 * @copyright 2015-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
$links  = [];
$routes = [
    'jurisdictions' => 'jurisdiction',
    'towns'         => 'town',
    'townships'     => 'township',
    'users'         => 'user',
    'subdivisions'  => 'subdivision'
];
foreach ($routes as $plural=>$singular) {
    if (parent::isAllowed($plural, 'index')) {
        $links[] = [
            'url'   => parent::generateUri("$plural.index"),
            'label' => $this->_([$singular, $plural, 2])
        ];
    }
}

if ($links) {
    $helper = $this->getHelper('dropdown');
    echo $helper->dropdown($links, $this->_('admin'), 'Administrator_menu');
}
