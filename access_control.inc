<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$ZEND_ACL = new Zend_Acl();
/**
 * Load the roles from the database
 */
$roles = new RoleList();
$roles->find();
foreach ($roles as $role) {
	$ZEND_ACL = $ZEND_ACL->addRole(new Zend_Acl_Role($role->getName()));
}
/**
 * Create resources for all the classes
 */
$ZEND_ACL->add(new Zend_Acl_Resource('AddressLocationChange'));
$ZEND_ACL->add(new Zend_Acl_Resource('Address'));
$ZEND_ACL->add(new Zend_Acl_Resource('AddressSanitation'));
$ZEND_ACL->add(new Zend_Acl_Resource('AddressStatusChange'));
$ZEND_ACL->add(new Zend_Acl_Resource('AddressStatus'));
$ZEND_ACL->add(new Zend_Acl_Resource('Annexation'));
$ZEND_ACL->add(new Zend_Acl_Resource('Building'));
$ZEND_ACL->add(new Zend_Acl_Resource('BuildingStatus'));
$ZEND_ACL->add(new Zend_Acl_Resource('BuildingType'));
$ZEND_ACL->add(new Zend_Acl_Resource('Contact'));
$ZEND_ACL->add(new Zend_Acl_Resource('Direction'));
$ZEND_ACL->add(new Zend_Acl_Resource('Jurisdiction'));
$ZEND_ACL->add(new Zend_Acl_Resource('Location'));
$ZEND_ACL->add(new Zend_Acl_Resource('LocationType'));
$ZEND_ACL->add(new Zend_Acl_Resource('Person'));
$ZEND_ACL->add(new Zend_Acl_Resource('Plat'));
$ZEND_ACL->add(new Zend_Acl_Resource('Precinct'));
$ZEND_ACL->add(new Zend_Acl_Resource('Purpose'));
$ZEND_ACL->add(new Zend_Acl_Resource('Role'));
$ZEND_ACL->add(new Zend_Acl_Resource('StreetName'));
$ZEND_ACL->add(new Zend_Acl_Resource('StreetNameType'));
$ZEND_ACL->add(new Zend_Acl_Resource('Street'));
$ZEND_ACL->add(new Zend_Acl_Resource('StreetStatus'));
$ZEND_ACL->add(new Zend_Acl_Resource('StreetType'));
$ZEND_ACL->add(new Zend_Acl_Resource('SubdivisionName'));
$ZEND_ACL->add(new Zend_Acl_Resource('Subdivision'));
$ZEND_ACL->add(new Zend_Acl_Resource('Subunit'));
$ZEND_ACL->add(new Zend_Acl_Resource('SubunitStatus'));
$ZEND_ACL->add(new Zend_Acl_Resource('SubunitType'));
$ZEND_ACL->add(new Zend_Acl_Resource('Town'));
$ZEND_ACL->add(new Zend_Acl_Resource('Township'));
$ZEND_ACL->add(new Zend_Acl_Resource('User'));

/**
 * Assign permissions to the resources
 */
// Administrator is allowed access to everything
$ZEND_ACL->allow('Administrator');

// Gis is allowed access to everything except Users, Roles, and People
$ZEND_ACL->allow('GIS');
$ZEND_ACL->deny('GIS','User');
$ZEND_ACL->deny('GIS','Role');
$ZEND_ACL->deny('GIS','Person');


// Engineering is only allowed to edit this stuff
$ZEND_ACL->allow('Engineering','Street');
$ZEND_ACL->allow('Engineering','Address');
$ZEND_ACL->allow('Engineering','Subunit');
