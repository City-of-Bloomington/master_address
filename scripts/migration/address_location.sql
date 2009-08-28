create table temp (
	lid number not null primary key,
	location_id number not null,
	location_type_id varchar2(40) not null,
	street_address_id number,
	subunit_id number,
	mailable_flag number,
	livable_flag number,
	common_name varchar2(100),
	active char(1) check (active in ('Y','N')),
	unique (location_id, street_address_id, subunit_id),
	foreign key (street_address_id) references mast_address (street_address_id),
	foreign key (subunit_id) references mast_address_subunits (subunit_id),
	foreign key (location_type_id) references addr_location_types_master (location_type_id)
);

create sequence location_lid_seq nocache;

insert into temp(lid, location_id, location_type_id, street_address_id, subunit_id,
	mailable_flag, livable_flag, common_name, active)
select location_lid_seq.nextval, location_id, location_type_id, street_address_id, subunit_id,
	mailable_flag, livable_flag, common_name, active
from address_location;


-- Manual changes
-- Drop table address_location
-- rename temp to address_location

create trigger location_trigger
before insert on address_location
for each row
begin
select location_lid_seq.nextval into :new.lid from dual;
end;
/

