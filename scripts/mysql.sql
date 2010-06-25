-- @copyright 2006-2009 City of Bloomington, Indiana
-- @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
-- @author Cliff Ingham <inghamn@bloomington.in.gov>
/* set FOREIGN_KEY_CHECKS=0 */
create table people (
	id int unsigned not null primary key auto_increment,
	firstname varchar(128) not null,
	lastname varchar(128) not null,
	email varchar(255) not null
) engine=InnoDB;
insert people values(1,'Administrator','','');

create table users (
	id int unsigned not null primary key auto_increment,
	person_id int unsigned not null unique,
	username varchar(30) not null unique,
	password varchar(32),
	authenticationMethod varchar(40) not null default 'LDAP',
	foreign key (person_id) references people(id)
) engine=InnoDB;
insert users values(1,1,'admin',md5('admin'),'local');

create table roles (
	id int unsigned not null primary key auto_increment,
	name varchar(30) not null unique
) engine=InnoDB;
insert roles values(1,'Administrator');

create table user_roles (
	user_id int unsigned not null,
	role_id int unsigned not null,
	primary key (user_id,role_id),
	foreign key(user_id) references users (id),
	foreign key(role_id) references roles (id)
) engine=InnoDB;
insert user_roles values(1,1);

create table towns_master (
	town_id int unsigned not null primary key auto_increment,
	description varchar(40),
	town_code varchar(9)
);

create table township_master (
	township_id int unsigned not null primary key auto_increment,
	name varchar(40),
	township_abbreviation char(2),
	quarter_code char(1)
);

create table plat_master (
	plat_id int unsigned not null primary key auto_increment,
	name varchar(120),
	township_id int unsigned not null,
	effective_start_date date,
	effective_end_date date,
	plat_type char(1),
	plat_cabinet varchar(5),
	envelope varchar(10),
	notes varchar(240),
	foreign key (township_id) references township_master(township_id)
);

create table voting_precincts (
	id int unsigned not null primary key auto_increment,
	precinct varchar(6) not null,
	precinct_name varchar(20),
	active char(1) not null,
	unique (precinct)
);

create table governmental_jurisdiction_mast (
	gov_jur_id int not null primary key,
	description varchar(20)
);

create table building_types_master (
	building_type_id int unsigned not null primary key auto_increment,
	description varchar(20) not null
);

create table buildings_status_lookup (
	status_code int unsigned not null primary key auto_increment,
	description varchar(240) not null
);

create table buildings (
	building_id int unsigned not null primary key auto_increment,
	building_type_id int unsigned not null,
	gis_tag varchar(20),
	building_name varchar(40),
	effective_start_date date not null default '2002-01-01',
	effective_end_date date,
	status_code int unsigned not null default 1,
	foreign key (building_type_id) references building_types_master(building_type_id),
	foreign key (status_code) references buildings_status_lookup(status_code)
);

create table mast_street_direction_master (
	id int unsigned not null primary key auto_increment,
	direction_code char(2) not null,
	description varchar(12) not null,
	unique (direction_code)
);

create table mast_street_type_suffix_master (
	id int unsigned not null primary key auto_increment,
	suffix_code varchar(8) not null,
	description varchar(240) not null,
	unique (suffix_code)
);

create table mast_addr_subunit_types_mast (
	id int unsigned not null primary key auto_increment,
	sudtype varchar(20) not null,
	description varchar(40) not null,
	unique (sudtype)
);

create view latest_subunit_status as
select z.*,s.status_code,l.description from mast_address_subunit_status s
left join mast_address_status_lookup l on s.status_code=l.status_code
right join (
	select subunit_id,max(start_date) as start_date
	from mast_address_subunit_status group by subunit_id
) z on (s.subunit_id=z.subunit_id and s.start_date=z.start_date);

create table addr_location_types_master (
	id int unsigned not null primary key auto_increment,
	location_type_id varchar(40) not null,
	description varchar(240),
	unique(id)

);

create table addr_location_purpose_mast (
	location_purpose_id int unsigned not null primary key auto_increment,
	description varchar(80) not null,
	type varchar(32) not null
);

create table mast_addr_assignment_contact (
	contact_id int unsigned not null primary key auto_increment,
	last_name varchar(30) not null,
	first_name varchar(20) not null,
	contact_type varchar(20) not null,
	phone_number varchar(12) not null,
	agency varchar(40) not null
);

create table mast_address_location_change (
	location_change_id int unsigned not null primary key auto_increment,
	location_id int unsigned not null,
	change_date date not null,
	notes varchar(240)
);

create table subdivision_master (
	subdivision_id int unsigned not null primary key auto_increment,
	township_id int unsigned not null,
	foreign key (township_id) references township_master(township_id)
);

create table subdivision_names (
	subdivision_name_id int unsigned not null primary key auto_increment,
	subdivision_id int not null,
	name varchar(100) not null,
	phase varchar(20),
	status varchar(20) not null,
	effective_start_date date,
	effective_end_date date,
	foreign key (subdivision_id) references subdivision_master(subdivision_id)
);

create table mast_street_name_type_master (
	id int unsigned not null primary key auto_increment,
	street_name_type varchar(20) not null,
	description varchar(240) not null,
	unique (street_name_type)
);

create table mast_street (
	street_id int unsigned not null primary key auto_increment,
	street_direction_code char(2),
	post_direction_suffix_code char(2),
	town_id int unsigned,
	status_code int unsigned not null,
	notes varchar(240),
	foreign key (street_direction_code) references mast_street_direction_master(direction_code),
	foreign key (post_direction_suffix_code) references mast_street_direction_master(direction_code),
	foreign key (town_id) references towns_master(town_id),
	foreign key (status_code) references mast_street_status_lookup(status_code)
);

create table mast_street_names (
	id int unsigned not null primary key auto_increment,
	street_id int unsigned not null,
	street_name varchar(60) not null,
	street_type_suffix_code varchar(8),
	street_name_type varchar(20),
	effective_start_date date not null default CURRENT_DATE,
	effective_end_date date,
	notes varchar(240),
	street_direction_code char(2),
	post_direction_suffix_code char(2),
	unique (street_id,street_name),
	foreign key (street_id) references mast_street(street_id),
	foreign key (street_type_suffix_code) references mast_street_type_suffix_master(suffix_code),
	foreign key (street_name_type) references mast_street_name_type_master(street_name_type)
);

create table mast_street_townships (
	street_id int unsigned not null,
	township_id int unsigned not null,
	primary key (street_id,township_id),
	foreign key (street_id) references mast_street(street_id),
	foreign key (township_id) references township_master(township_id)
);

create table mast_street_subdivision (
	street_id int unsigned not null,
	subdivision_id int unsigned not null,
	primary key (street_id,subdivision_id),
	foreign key (street_id) references mast_street(street_id),
	foreign key (subdivision_id) references subdivision_master(subdivision_id)
);

create table quarter_section_master (
	quarter_section char(2) not null primary key
);

create table trash_pickup_master (
	trash_pickup_day varchar(20) not null primary key
);

create table trash_recycle_week_master (
	recycle_week varchar(20) not null primary key
);

create table mast_address (
	street_address_id int unsigned not null primary key auto_increment,
	street_number varchar(20),
	street_id int unsigned not null,
	address_type varchar(20) not null,
	tax_jurisdiction char(3),
	jurisdiction_id int unsigned not null,
	gov_jur_id int unsigned not null,
	township_id int unsigned,
	section varchar(20),
	quarter_section char(2),
	subdivision_id int unsigned,
	plat_id int unsigned,
	plat_lot_number varchar(20),
	street_address_2 varchar(40),
	city varchar(20),
	state varchar(3),
	zip varchar(6),
	zipplus4 varchar(6),
	census_block_fips_code varchar(20),
	state_plane_x_coordinate int unsigned,
	state_plane_y_coordinate int unsigned,
	latitude float(8,6),
	longitude float(8,6),
	notes varchar(240),
	status_code int unsigned,
	foreign key (street_id) references mast_street(street_id),
	foreign key (quarter_section) references quarter_section_master(quarter_section),
	foreign key (township_id) references township_master(township_id),
	foreign key (subdivision_id) references subdivision_master(subdivision_id),
	foreign key (jurisdiction_id) references addr_jurisdiction_master(jurisdiction_id),
	foreign key (gov_jur_id) references governmental_jurisdiction_mast(gov_jur_id),
	foreign key (plat_id) references plat_master(plat_id)
);

create table mast_address_status (
	id int unsigned not null primary key auto_increment,
	street_address_id int unsigned not null,
	status_code int unsigned not null,
	start_date datetime not null,
	end_date datetime,
	unique (street_address_id,start_date),
	foreign key (status_code) references mast_address_status_lookup (status_code),
	foreign key (street_address_id) references mast_address (street_address_id)
);

create view mast_address_latest_status as
select z.*,s.status_code,l.description from mast_address_status s
left join mast_address_status_lookup l on s.status_code=l.status_code
right join (
	select street_address_id,max(start_date) as start_date
	from mast_address_status group by street_address_id
) z on (s.street_address_id=z.street_address_id and s.start_date=z.start_date);

create table address_change_log (
	street_address_id int unsigned not null,
	user_id int unsigned not null,
	action varchar(20) not null,
	contact_id int unsigned not null,
	rationale varchar(255),
	date_changed timestamp not null default CURRENT_DATE,
	foreign key (street_address_id) references mast_address(street_address_id),
	foreign key (user_id) references users(id),
	foreign key (contact_id) references mast_addr_assignment_contact(contact_id)
);

create table mast_address_subunit_status (
	id int unsigned not null primary key auto_increment,
	subunit_id int unsigned not null,
	street_address_id int unsigned not null,
	status_code int unsigned not null,
	start_date datetime not null,
	end_date datetime,
	unique (subunit_id,start_date),
	foreign key (status_code) references mast_address_status_lookup (status_code),
	foreign key (street_address_id) references mast_address (street_address_id),
	foreign key (subunit_id) references mast_address_subunits (subunit_id)
);

create table mast_address_subunits (
	subunit_id int unsigned not null primary key auto_increment,
	street_address_id int unsigned not null,
	sudtype varchar(20) not null,
	street_subunit_identifier varchar(20) not null,
	notes varchar(240),
	foreign key (street_address_id) references mast_address (street_address_id),
	foreign key (sudtype) references mast_addr_subunit_types_mast (sudtype)
);

create table annexations (
	id int not null primary key auto_increment,
	ordinance_number varchar(12) not null,
	township_id int unsigned,
	name varchar(40),
	passed_date date,
	effective_start_date date,
	annexation_type int unsigned,
	acres decimal(6,2),
	square_miles decimal(4,2),
	estimate_population int unsigned,
	dwelling_units int unsigned,
	unique (ordinance_number)
);
