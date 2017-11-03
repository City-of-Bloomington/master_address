<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

$ZEND_ACL = new Acl();
$ZEND_ACL->addRole(new Role('Anonymous'))
		 ->addRole(new Role('Public'),   'Anonymous')
		 ->addRole(new Role('Engineering'), 'Public')
		 ->addRole(new Role('GIS'))
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
$ZEND_ACL->allow(null, ['home'], ['index']);

$ZEND_ACL->allow('GIS');
$ZEND_ACL->deny ('GIS', 'users');

$ZEND_ACL->allow('Administrator');
