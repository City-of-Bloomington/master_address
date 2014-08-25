alter table eng.mast_address_subunits add state_plane_x_coordinate number;
alter table eng.mast_address_subunits add state_plane_y_coordinate number;
alter table eng.mast_address_subunits add latitude number;
alter table eng.mast_address_subunits add longitude number;
alter table eng.mast_address_subunits add usng_coordinate varchar2(20);
alter table eng.mast_address          add usng_coordinate varchar2(20);

alter table eng.mast_address drop column tax_jurisdiction;
alter table eng.mast_address drop column census_block_fips_code;

-- Split street_number into prefix, number, and suffix
alter table eng.mast_address add street_number_prefix varchar2(10);
alter table eng.mast_address add street_number_suffix varchar2(10);

alter table eng.mast_address drop constraint mast_address_uk1;
drop index eng.mast_address_uk1;
alter table eng.mast_address add  constraint mast_address_uk1 unique(street_id, street_number_prefix, street_number, street_number_suffix);

update eng.mast_address a set a.street_number=trim(a.street_number);

-- Clean up all the street_number fractions
update eng.mast_address a
set a.street_number       = trim(substr(a.street_number, 1, instr(a.street_number, '/')-2)),
    a.street_number_suffix= trim(substr(a.street_number,    instr(a.street_number, '/')-1))
where a.street_number like '%/%';

-- Clean up any other non-numeric street numbers
update eng.mast_address a
set a.street_number        = trim(substr(a.street_number, 1, regexp_instr(a.street_number, '[^0-9]')-1)),
    a.street_number_suffix = trim(substr(a.street_number,    regexp_instr(a.street_number, '[^0-9]')))
where regexp_like(a.street_number, '[^0-9]');

delete from eng.address_location where street_address_id in (
    select street_address_id from eng.mast_address where street_number is null
    union
    select street_address_id from eng.mast_address where street_number='2ND'
    union
    select street_address_id from eng.mast_address where regexp_like(street_number, '^0+')
);
delete from eng.mast_address_status where street_address_id in (
    select street_address_id from eng.mast_address where street_number is null
    union
    select street_address_id from eng.mast_address where street_number='2ND'
    union
    select street_address_id from eng.mast_address where regexp_like(street_number, '^0+')
);
delete from eng.mast_address where street_number is null;
delete from eng.mast_address where street_number='2ND';
delete from eng.mast_address where regexp_like(street_number, '^0+');

-- Convert street_number into a number column, instead of varchar
alter table eng.mast_address add temp number(10,0);
update eng.mast_address set temp=to_number(street_number);

alter table eng.mast_address drop constraint mast_address_uk1;
drop index eng.mast_address_uk1;

alter table eng.mast_address drop column street_number;
alter table eng.mast_address rename column temp to street_number;
alter table eng.mast_address
add constraint mast_address_uk1 unique(street_id, street_number_prefix, street_number, street_number_suffix);
alter table eng.mast_address modify (street_number not null);

-- Drop street direction and post direction
-- these were moved to street_names a long time ago
alter table eng.mast_street drop column street_direction_code;
alter table eng.mast_street drop column post_direction_suffix_code;

alter table eng.address_location drop column common_name;

alter table eng.mast_address_subunits rename column street_subunit_identifier to subunit_identifier;

delete from eng.mast_address_sanitation
where trash_pickup_day      is null
  and recycle_week          is null
  and large_item_pickup_day is null;

-- Address Contacts new fields
create table eng.contactStatus (
	id number not null primary key,
	status varchar2(20) not null
);
grant select on eng.contactStatus to public;
create sequence  eng.contactStatus_id_s nocache;
create trigger   eng.contactStatus_trigger
before insert on eng.contactStatus
for each row
begin
select contactStatus_id_s.nextval into :new.id from dual;
end;
/
insert into eng.contactStatus (status) values('Current');
insert into eng.contactStatus (status) values('Historic');

alter table eng.mast_addr_assignment_contact add email        varchar2(128);
alter table eng.mast_addr_assignment_contact add address      varchar2(128);
alter table eng.mast_addr_assignment_contact add city         varchar2(128);
alter table eng.mast_addr_assignment_contact add state        char(2);
alter table eng.mast_addr_assignment_contact add zip          varchar2(10);
alter table eng.mast_addr_assignment_contact add status_id    number default 1 not null;
alter table eng.mast_addr_assignment_contact add notification char(2);
alter table eng.mast_addr_assignment_contact add coordination char(2);
alter table eng.mast_addr_assignment_contact add foreign key (status_id) references eng.contactStatus(id);

alter table eng.mast_address rename column jurisdiction_id to addr_jurisdiction_id;
alter table eng.mast_address drop column numeric_street_number;
