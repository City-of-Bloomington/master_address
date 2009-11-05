create table address_change_log (
	street_address_id number not null,
	user_id number not null,
	action varchar2(20) not null,
	contact_id number,
	notes varchar2(255),
	action_date date default sysdate,
	foreign key (street_address_id) references mast_address(street_address_id),
	foreign key (user_id) references users(id),
	foreign key (contact_id) references mast_addr_assignment_contact(contact_id)
);

create table street_change_log (
	street_id number not null,
	user_id number not null,
	action varchar2(20) not null,
	contact_id number,
	notes varchar2(255),
	action_date date default sysdate,
	foreign key (street_id) references mast_street(street_id),
	foreign key (user_id) references users(id),
	foreign key (contact_id) references mast_addr_assignment_contact(contact_id)
);

create table subunit_change_log (
	subunit_id number not null,
	user_id number not null,
	action varchar2(20) not null,
	contact_id number,
	notes varchar2(255),
	action_date date default sysdate,
	foreign key (subunit_id) references mast_address_subunits(subunit_id),
	foreign key (user_id) references users(id),
	foreign key (contact_id) references mast_addr_assignment_contact(contact_id)
);


insert into address_change_log (street_address_id,user_id,contact_id,notes,action_date,action)
select a.street_address_id,1, c.contact_id, a.notes, a.action_date,
decode(a.action,
  'RETIRED LOCATION','retired',
  'CORRECTED','corrected',
  'REASSIGNED','reassigned',
  'MOVED TO LOCATION','moved to location',
  'UNRETIRED LOCATION','unretired',
  'ASSIGN','assigned',
  'UNRETIRED','unretired',
  'REMOVED','retired',
  'ACTIVE','activated',
  'READDRESSED','readdressed',
  'RETIRED','retired')
from mast_address_assignment_hist a
left join mast_addr_assignment_contact c on a.contact_id=c.contact_id
where street_address_id in (select street_address_id from mast_address)
and subunit_id is null;


insert into subunit_change_log (subunit_id,user_id,contact_id,notes,action_date,action)
select a.subunit_id,1, c.contact_id, a.notes, a.action_date,
decode(a.action,
  'RETIRED LOCATION','retired',
  'CORRECTED','corrected',
  'REASSIGNED','reassigned',
  'MOVED TO LOCATION','moved to location',
  'UNRETIRED LOCATION','unretired',
  'ASSIGN','assigned',
  'UNRETIRED','unretired',
  'REMOVED','retired',
  'ACTIVE','activated',
  'READDRESSED','readdressed',
  'RETIRED','retired')
from mast_address_assignment_hist a
left join mast_addr_assignment_contact c on a.contact_id=c.contact_id
where subunit_id in (select subunit_id from mast_address_subunits)
and subunit_id is not null;
