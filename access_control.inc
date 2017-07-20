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
 * Create resources for all the Controllers
 */
$ZEND_ACL->addResource(new Resource('index'));
$ZEND_ACL->addResource(new Resource('people'));
$ZEND_ACL->addResource(new Resource('users'));
$ZEND_ACL->addResource(new Resource('login'));

$ZEND_ACL->addResource(new Resource('AddressLocationChange'));
$ZEND_ACL->addResource(new Resource('Address'));
$ZEND_ACL->addResource(new Resource('AddressSanitation'));
$ZEND_ACL->addResource(new Resource('AddressStatusChange'));
$ZEND_ACL->addResource(new Resource('addressStatuses'));
$ZEND_ACL->addResource(new Resource('Annexation'));
$ZEND_ACL->addResource(new Resource('Building'));
$ZEND_ACL->addResource(new Resource('BuildingStatus'));
$ZEND_ACL->addResource(new Resource('BuildingType'));
$ZEND_ACL->addResource(new Resource('ChangeLog'));
$ZEND_ACL->addResource(new Resource('Contact'));
$ZEND_ACL->addResource(new Resource('contactStatuses'));
$ZEND_ACL->addResource(new Resource('directions'));
$ZEND_ACL->addResource(new Resource('Intersection'));
$ZEND_ACL->addResource(new Resource('jurisdictions'));
$ZEND_ACL->addResource(new Resource('Location'));
$ZEND_ACL->addResource(new Resource('LocationType'));
$ZEND_ACL->addResource(new Resource('Places'));
$ZEND_ACL->addResource(new Resource('Plat'));
$ZEND_ACL->addResource(new Resource('Precinct'));
$ZEND_ACL->addResource(new Resource('Purpose'));
$ZEND_ACL->addResource(new Resource('Report'));
$ZEND_ACL->addResource(new Resource('StreetName'));
$ZEND_ACL->addResource(new Resource('StreetNameType'));
$ZEND_ACL->addResource(new Resource('streets'));
$ZEND_ACL->addResource(new Resource('streetStatuses'));
$ZEND_ACL->addResource(new Resource('streetTypes'));
$ZEND_ACL->addResource(new Resource('SubdivisionName'));
$ZEND_ACL->addResource(new Resource('Subdivision'));
$ZEND_ACL->addResource(new Resource('Subunit'));
$ZEND_ACL->addResource(new Resource('SubunitStatus'));
$ZEND_ACL->addResource(new Resource('subunitTypes'));
$ZEND_ACL->addResource(new Resource('towns'));
$ZEND_ACL->addResource(new Resource('townships'));


// Permissions for unauthenticated browsing
$ZEND_ACL->allow(null, 'login');
$ZEND_ACL->allow(null, ['index'], ['index']);

// Engineering is only allowed to edit this stuff
#$ZEND_ACL->allow('Engineering','streets'   );
#$ZEND_ACL->allow('Engineering','addresses' );
#$ZEND_ACL->allow('Engineering','subunits'  );
#$ZEND_ACL->allow('Engineering','plats'     );
#$ZEND_ACL->allow('Engineering','contacts'  );
#$ZEND_ACL->allow('Engineering','changeLogs');
#$ZEND_ACL->allow('Engineering','reports'   );

$ZEND_ACL->allow('GIS');
$ZEND_ACL->deny ('GIS', 'users');

$ZEND_ACL->allow('Administrator');
