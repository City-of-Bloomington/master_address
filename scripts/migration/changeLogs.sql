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


