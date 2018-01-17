<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
declare (strict_types=1);
use Domain\Authorization\AuthorizationService as Auth;

$permitted = [
    Auth::ROLE_ANONYMOUS => [
        'Domain\Addresses\UseCases\Search\Search'
    ]
];

$permitted[Auth::ROLE_PUBLIC] = array_merge($permitted[Auth::ROLE_ANONYMOUS], [
]);

$permitted[Auth::ROLE_ENGINEER] = array_merge($permitted[Auth::ROLE_PUBLIC], [
]);

$permitted[Auth::ROLE_GIS] = array_merge($permitted[Auth::ROLE_ENGINEER], [
    'Domain\Towns\UseCases\Info\Info',
    'Domain\Towns\UseCases\Search\Search'
]);

$permitted[Auth::ROLE_ADMIN] = array_merge($permitted[Auth::ROLE_GIS], [
]);

$ACL = new Auth($permitted);
