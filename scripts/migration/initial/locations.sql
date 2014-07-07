create table temp (
	location_id number not null enable,
	location_type_id varchar2(40) not null enable,
	street_address_id number,
	subunit_id number,
	mailable_flag varchar2(7),
	livable_flag varchar2(7),
	common_name varchar2(100),
	active char(1) not null enable,
	unique (location_id, street_address_id, subunit_id),
	foreign key (street_address_id) references mast_address (street_address_id),
	foreign key (subunit_id) references mast_address_subunits (subunit_id),
	foreign key (location_type_id) references addr_location_types_master (location_type_id)
);

insert into temp (location_id, location_type_id, street_address_id, subunit_id, mailable_flag, livable_flag, common_name, active)
select * from address_location;

update temp set mailable_flag='yes' where mailable_flag='1';
update temp set mailable_flag='no' where mailable_flag='0';
update temp set mailable_flag='unknown' where mailable_flag is null;

update temp set livable_flag='yes' where livable_flag='1';
update temp set livable_flag='no' where livable_flag='0';
update temp set livable_flag='unknown' where livable_flag is null;

drop table address_location;
rename temp to address_location;

-- Manually add in the constraints