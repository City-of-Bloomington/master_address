alter table master_address.users modify authenticationMethod varchar(40) default 'Employee';
update master_address.users set authenticationMethod='Employee' where authenticationMethod='LDAP';
