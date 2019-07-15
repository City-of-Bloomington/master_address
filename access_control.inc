<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;
$ZEND_ACL = new Acl();
$ZEND_ACL->addRole(new Role('Anonymous'))
		 ->addRole(new Role('Public'), 'Anonymous')
		 ->addRole(new Role('addressedit'))
		 ->addRole(new Role('Administrator'));
/**
 * Create resources for all the routes
 */
foreach ($ROUTES->getRoutes() as $r) {
    list($resource, $permission) = explode('.', $r->name);
    if (!$ZEND_ACL->hasResource($resource)) {
         $ZEND_ACL->addResource(new Resource($resource));
    }
}
// Permissions for unauthenticated browsing
$ZEND_ACL->allow(null, 'login');
$ZEND_ACL->allow(null, 'home', 'index');
$ZEND_ACL->allow(null, 'addresses', ['index', 'view', 'parse']);
$ZEND_ACL->allow(null, 'streets',   ['index', 'view', 'intersections', 'intersectingStreets']);
$ZEND_ACL->allow(null, 'subunits',  ['index', 'view']);

$ZEND_ACL->allow('addressedit');
$ZEND_ACL->deny ('addressedit', 'users');
$ZEND_ACL->allow('Administrator');
