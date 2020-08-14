<?php
/**
 * @copyright 2018-2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

use Laminas\Permissions\Acl\Acl;
use Laminas\Permissions\Acl\Role\GenericRole as Role;
use Laminas\Permissions\Acl\Resource\GenericResource as Resource;
$ACL = new Acl();
$ACL->addRole(new Role('Anonymous'))
    ->addRole(new Role('viewer'), 'Anonymous')
    ->addRole(new Role('addressedit'))
    ->addRole(new Role('Administrator'));
/**
 * Create resources for all the routes
 */
foreach ($ROUTES->getRoutes() as $r) {
    list($resource, $permission) = explode('.', $r->name);
    if (!$ACL->hasResource($resource)) {
         $ACL->addResource(new Resource($resource));
    }
}
// Permissions for unauthenticated browsing
$ACL->allow(null, 'login');
$ACL->allow(null, 'home', 'index');
$ACL->allow(null, ['addresses', 'streets', 'subunits', 'subdivisions', 'plats'], ['index', 'view']);
$ACL->allow(null, 'addresses', 'parse');
$ACL->allow(null, 'streets',  ['intersections', 'intersectingStreets']);

$ACL->allow('viewer', 'reports');

$ACL->allow('addressedit');
$ACL->deny ('addressedit', 'users');

$ACL->allow('Administrator');
