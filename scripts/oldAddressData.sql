---------------------------------------------------------------
-- eng stuff
---------------------------------------------------------------
create table address_location (
	location_id int unsigned not null,
	location_type_id varchar(40) not null,
	street_address_id int unsigned,
	subunit_id int unsigned,
	mailable_flag tinyint(1) unsigned,
	livable_flag tinyint(1) unsigned,
	common_name varchar(100),
	active char(1) not null,
	key (location_id,street_address_id,subunit_id),
	key (location_id),
	key (location_type_id),
	key (street_address_id),
	key (subunit_id)
) engine=MyISAM;

-- foreign key 	location_type_id 	1 	eng 	addr_location_types_master 	location_type_id
-- foreign key 	street_address_id 	1 	eng 	mast_address 	street_address_id
-- foreign key 	subunit_id 	1 	eng 	mast_address_subunits 	subunit_id



create table addr_jurisdiction_master (
	jurisdiction_id int unsigned not null primary key auto_increment,
	description varchar(20)
) engine=MyISAM;



create table addr_location_purposes (
	location_id int unsigned not null,
	location_purpose_id int unsigned not null,
	contact_information varchar(20),
	street_address_id int unsigned,
	subunit_id int unsigned,
	primary key (location_id,location_purpose_id)
) engine=MyISAM;

-- foreign key 	location_purpose_id 	1 	eng 	addr_location_purpose_mast 	location_purpose_id



create table addr_location_purpose_mast (
	location_purpose_id int unsigned not null primary key auto_increment,
	description varchar(80),
	type varchar(32)
) engine=MyISAM;



create table addr_location_relationships (
	location_id int unsigned not null,
	location_relationship_type char(18) not null,
	second_location_id int unsigned not null,
	relationship_name varchar(20),
	primary key (location_id,location_relationship_type,second_location_id)
) engine=MyISAM;

-- foreign key 	location_relationship_type 	1 	eng 	addr_location_rel_types_mast 	location_relationship_type



create table addr_location_rel_types_mast (
	location_relationship_type char(18) not null primary key
) engine=MyISAM;



create table addr_location_types_master (
	location_type_id varchar(40) not null primary key,
	description varchar(240)
) engine=MyISAM;



create table governmental_jurisdiction_mast (
	gov_jur_id int unsigned not null primary key auto_increment,
	description varchar(20)
) engine=MyISAM;



create table mast_address (
	street_address_id int unsigned not null primary key auto_increment,
	street_number varchar(20),
	street_id int unsigned not null,
	address_type varchar(20) not null,
	tax_jurisdiction char(3),
	jurisdiction_id int unsigned not null,
	gov_jur_id int unsigned not null,
	township_id tinyint(2) unsigned,
	section varchar(20),
	quarter_section char(2),
	subdivision_id int unsigned,
	plat_id int unsigned,
	plat_lot_number int unsigned,
	street_address_2 varchar(40),
	city varchar(20),
	state varchar(3),
	zip varchar(6),
	zipplus4 varchar(6),
	census_block_fips_code varchar(20),
	state_plane_x_coordinate int,
	state_plane_y_coordinate int,
	latitude float(10,6),
	longitude float(10,6),
	notes varchar(240),
	status_code tinyint(1) unsigned,
	key (street_id),
	key (address_type),
	key (jurisdiction_id),
	key (gov_jur_id)
) engine=MyISAM;

-- foreign key 	jurisdiction_id 	1 	eng 	addr_jurisdiction_master 	jurisdiction_id
-- foreign key 	gov_jur_id 	1 	eng 	governmental_jurisdiction_mast 	gov_jur_id
-- foreign key 	street_id 	1 	eng 	mast_street 	street_id
-- foreign key 	plat_id 	1 	eng 	plat_master 	plat_id
-- foreign key 	quarter_section 	1 	eng 	quarter_section_master 	quarter_section
-- foreign key 	subdivision_id 	1 	eng 	subdivision_master 	subdivision_id
-- foreign key 	township_id 	1 	eng 	township_master 	township_id



create table mast_address_annexation (
	ordinance_number varchar(12) not null primary key,
	street_address_id int unsigned not null,
	key (street_address_id)
) engine=MyISAM;

-- foreign key 	street_address_id 	1 	eng 	mast_address 	street_address_id



create table mast_address_assignment_hist (
	location_id int unsigned not null,
	action_date date not null,
	action varchar(20) not null,
	contact_id int unsigned,
	notes varchar(240),
	street_address_id int unsigned not null,
	subunit_id int unsigned,
	key (location_id,action_date,action,street_address_id)
) engine=MyISAM;



create table mast_address_location_change (
	location_change_id int unsigned not null primary key auto_increment,
	location_id int unsigned,
	old_location_id int unsigned,
	change_date date,
	notes varchar(240)
) engine=MyISAM;



create table mast_address_location_status (
	status_code tinyint unsigned not null,
	effective_start_date date not null,
	location_id int unsigned not null,
	effective_end_date date,
	primary key (status_code,effective_start_date,location_id),
	key (location_id)
) engine=MyISAM;

-- foreign key 	status_code 	1 	eng 	mast_address_status_lookup 	status_code



create table mast_address_sanitation (
	street_address_id int unsigned not null primary key,
	trash_pickup_day varchar(20),
	recycle_week char(1),
	large_item_pickup_day varchar(20)
) engine=MyISAM;

-- foreign key 	street_address_id 	1 	eng 	mast_address 	street_address_id
-- foreign key 	large_item_pickup_day 	1 	eng 	trash_large_item_pickup_master 	large_item_pickup_day
-- foreign key 	trash_pickup_day 	1 	eng 	trash_pickup_master 	trash_pickup_day
-- foreign key 	recycle_week 	1 	eng 	trash_recycle_week_master 	recycle_week



create table mast_address_status (
	street_address_id int unsigned not null primary key,
	status_code tinyint(1) unsigned,
	start_date date,
	end_date date
) engine=MyISAM;

-- foreign key 	street_address_id 	1 	eng 	mast_address 	street_address_id
-- foreign key 	status_code 	1 	eng 	mast_address_status_lookup 	status_code



create table mast_address_status_lookup (
	status_code tinyint(1) unsigned not null primary key auto_increment,
	description varchar(240)
) engine=MyISAM;



create table mast_address_subunits (
	subunit_id int unsigned not null primary key auto_increment,
	street_address_id int unsigned not null,
	sudtype varchar(20) not null,
	street_subunit_identifier varchar(20) not null,
	notes varchar(240),
	key (street_address_id),
	key (sudtype),
	key (street_subunit_identifier)
) engine=MyISAM;

-- foreign key 	street_address_id 	1 	eng 	mast_address 	street_address_id
-- foreign key 	sudtype 	1 	eng 	mast_addr_subunit_types_mast 	sudtype



create table mast_address_subunit_status (
	subunit_id int unsigned not null primary key auto_increment,
	street_address_id int unsigned,
	status_code tinyint(1) unsigned,
	start_date date,
	end_date date
) engine=MyISAM;

-- foreign key 	street_address_id 	1 	eng 	mast_address 	street_address_id
-- foreign key 	status_code 	1 	eng 	mast_address_status_lookup 	status_code
-- foreign key 	subunit_id 	1 	eng 	mast_address_subunits 	subunit_id



create table mast_addr_assgn_hist_temp (
	location_id int unsigned not null,
	action_date date,
	action varchar(20),
	contact_id int unsigned,
	notes varchar(240),
	key (location_id)
) engine=MyISAM;



create table mast_addr_assignment_contact (
	contact_id int unsigned not null primary key auto_increment,
	last_name varchar(30),
	first_name varchar(20),
	contact_type varchar(20),
	phone_number varchar(12),
	agency varchar(40)
) engine=MyISAM;



create table mast_addr_subunit_types_mast (
	sudtype varchar(20) not null primary key,
	description varchar(40)
) engine=MyISAM;



create table mast_street (
	street_id int unsigned not null primary key auto_increment,
	street_direction_code char(2),
	post_direction_suffix_code char(2),
	town_id tinyint(2) unsigned,
	status_code tinyint(1) unsigned not null,
	notes varchar(240),
	key (status_code)
) engine=MyISAM;

-- foreign key 	street_direction_code 	1 	eng 	mast_street_direction_master 	direction_code
-- foreign key 	post_direction_suffix_code 	1 	eng 	mast_street_direction_master 	direction_code
-- foreign key 	status_code 	1 	eng 	mast_street_status_lookup 	status_code
-- foreign key 	town_id 	1 	eng 	towns_master 	town_id



create table mast_street_addr_jurisdictions (
	street_id int unsigned not null,
	jurisdiction_id int unsigned not null,
	key (street_id),
	key (jurisdiction_id)
) engine=MyISAM;

-- foreign key 	jurisdiction_id 	1 	eng 	addr_jurisdiction_master 	jurisdiction_id
-- foreign key 	street_id 	1 	eng 	mast_street 	street_id



create table mast_street_direction_master (
	direction_code char(2) not null primary key,
	description varchar(12)
) engine=MyISAM;




create table mast_street_gov_jurisdictions (
	street_id int unsigned not null,
	gov_jur_id int unsigned not null,
	primary key (street_id,gov_jur_id)
) engine=MyISAM;

-- foreign key 	gov_jur_id 	1 	eng 	governmental_jurisdiction_mast 	gov_jur_id
-- foreign key 	street_id 	1 	eng 	mast_street 	street_id



create table mast_street_names (
	street_id int unsigned not null,
	street_name varchar(60) not null,
	street_type_suffix_code varchar(8),
	street_name_type varchar(20),
	effective_start_date date,
	effective_end_date date,
	notes varchar(240),
	street_direction_code char(2),
	post_direction_suffix_code char(2),
	primary key (street_id,street_name)
) engine=MyISAM;

-- foreign key 	street_id 	1 	eng 	mast_street 	street_id
-- foreign key 	street_name_type 	1 	eng 	mast_street_name_type_master 	street_name_type
-- foreign key 	street_type_suffix_code 	1 	eng 	mast_street_type_suffix_master 	suffix_code



create table mast_street_name_type_master (
	street_name_type varchar(20) not null primary key,
	description varchar(240)
) engine=MyISAM;



create table mast_street_plat (
	street_id int unsigned not null,
	plat_id int unsigned not null,
	primary key (street_id,plat_id)
) engine=MyISAM;

-- foreign key 	street_id 	1 	eng 	mast_street 	street_id
-- foreign key 	plat_id 	1 	eng 	plat_master 	plat_id



create table mast_street_section (
	street_id int unsigned not null,
	section int unsigned not null,
	primary key (street_id,section)
) engine=MyISAM;

-- foreign key 	street_id 	1 	eng 	mast_street 	street_id



create table mast_street_state_road (
	street_id int unsigned not null,
	state_road_id int unsigned not null,
	primary key (street_id,state_road_id)
) engine=MyISAM;

-- foreign key 	street_id 	1 	eng 	mast_street 	street_id
-- foreign key 	state_road_id 	1 	eng 	state_road_master 	state_road_id



create table mast_street_status_lookup (
	status_code tinyint(1) unsigned not null primary key auto_increment,
	description varchar(240)
) engine=MyISAM;



create table mast_street_subdivision (
	street_id int unsigned not null,
	subdivision_id int unsigned not null,
	primary key (street_id,subdivision_id)
) engine=MyISAM;

-- foreign key 	street_id 	1 	eng 	mast_street 	street_id
-- foreign key 	subdivision_id 	1 	eng 	subdivision_master 	subdivision_id



	create table mast_street_townships (
		street_id int unsigned not null,
		township_id tinyint(2) unsigned not null,
		primary key (street_id,township_id)
	) engine=MyISAM;

-- foreign key 	street_id 	1 	eng 	mast_street 	street_id
-- foreign key 	township_id 	1 	eng 	township_master 	township_id



create table mast_street_type_suffix_master (
	suffix_code varchar(8) not null primary key,
	description varchar(240)
) engine=MyISAM;






create table plat_master (
	plat_id int unsigned not null primary key auto_increment,
	name varchar(120),
	township_id tinyint(2) unsigned,
	effective_start_date date,
	effective_end_date date,
	plat_type char(1),
	plat_cabinet varchar(5),
	envelope varchar(15),
	notes varchar(240)
) engine=MyISAM;

-- foreign key 	township_id 	1 	eng 	township_master 	township_id



create table quarter_section_master (
	quarter_section char(2) not null primary key
) engine=MyISAM;



create table state_road_master (
	state_road_id int unsigned not null primary key auto_increment,
	description varchar(40),
	abbreviation varchar(10)
) engine=MyISAM;



create table subdivision_master (
	subdivision_id int unsigned not null primary key auto_increment,
	township_id tinyint(2) unsigned
) engine=MyISAM;

-- foreign key 	township_id 	1 	eng 	township_master 	township_id



create table subdivision_names (
	subdivision_id int unsigned not null,
	subdivision_name_id int unsigned not null primary key auto_increment,
	name varchar(100) not null,
	phase varchar(20),
	status varchar(20),
	effective_start_date date,
	effective_end_date date,
	key (subdivision_id),
	key (name)
) engine=MyISAM;

-- foreign key 	subdivision_id 	1 	eng 	subdivision_master 	subdivision_id



create table township_master (
	township_id tinyint(2) unsigned not null primary key auto_increment,
	name varchar(40),
	township_abbreviation char(2),
	quarter_code char(1)
) engine=MyISAM;

create table towns_master (
	town_id tinyint(2) unsigned not null primary key,
	description varchar(40)
) engine=MyISAM;


create table trash_large_item_pickup_master (
	large_item_pickup_day varchar(20) not null primary key
) engine=MyISAM;



create table trash_pickup_master (
	trash_pickup_day varchar(20) not null primary key
) engine=MyISAM;



create table trash_recycle_week_master (
	recycle_week varchar(20) not null primary key
) engine=MyISAM;

-------------------------------------------------------
-- gis stuff
-------------------------------------------------------

create table annexations (
	ordinance_number varchar(12) not null primary key,
	township_id tinyint(2) unsigned,
	name varchar(40),
	passed_date date,
	effective_start_date date,
	annexation_type int unsigned,
	acres int unsigned,
	square_miles int unsigned,
	estimate_population int unsigned,
	dwelling_units int unsigned
) engine=MyISAM;




create table buildings (
	building_id int unsigned not null primary key auto_increment,
	building_type_id tinyint(2) unsigned not null,
	gis_tag varchar(20),
	building_name varchar(40),
	effective_start_date date,
	effective_end_date date,
	status_code tinyint(1) unsigned,
	key (building_type_id)
) engine=MyISAM;

-- foreign key 	status_code 	1 	gis 	buildings_status_lookup 	status_code
-- foreign key 	building_type_id 	1 	gis 	building_types_master 	building_type_id



create table buildings_status_lookup (
	status_code tinyint(1) unsigned not null primary key auto_increment,
	description varchar(240)
) engine=MyISAM;




create table building_address_location (
	location_id int unsigned not null,
	building_id int unsigned not null,
	primary key (location_id,building_id),
	key (building_id),
	key (location_id)
) engine=MyISAM;

-- foreign key 	building_id 	1 	gis 	buildings 	building_id



create table building_types_master (
	building_type_id tinyint(2) unsigned not null primary key auto_increment,
	description varchar(20)
) engine=MyISAM;



create table locations_classes (
	location_id int unsigned,
	location_class varchar(40),
	key (location_id)
) engine=MyISAM;



create table mast_address_parcel (
	parcel_id char(18) not null primary key,
	street_address_id int unsigned
) engine=MyISAM;

-- foreign key 	parcel_id 	1 	gis 	parcel 	parcel_id



create table parcel (
	parcel_id char(18) not null primary key,
	effective_start_date date,
	effective_end_date date
) engine=MyISAM;

-- Temp table to put the segment dump file into mysql for easier reference.
create table segments (
	tag varchar(8) not null,
	direction char(1),
	name varchar(128),
	type_suf char(4),
	post_dir char(1),
	town varchar(10),
	class char(5),
	lowadd int(4) unsigned,
	highadd int(4) unsigned,
	level char(1),
	leftlow int(4) unsigned,
	lefthigh int(4) unsigned,
	rightlow int(4) unsigned,
	righthigh int(4) unsigned,
	rcinode1 char(8),
	rcinode2 char(8),
	low_node char(8),
	high_node char(8),
	low_x int(7) unsigned,
	low_y int(7) unsigned,
	high_x int(7) unsigned,
	high_y int(7) unsigned,
	speed tinyint(2) unsigned,
	travel tinyint(1) unsigned,
	travel_d varchar(10),
	indotID varchar(10),
	streetID int unsigned,
	complete char(2),
	comments text,
	rclback char(8),
	rclahead char(8),
	class_row int(4),
	maparea char(3),
	fullname varchar(128),
	location varchar(30),
	maintain varchar(30),
	lastdate date,
	up_act varchar(30),
	up_by varchar(15),
	seg_stat varchar(10),
	key (tag),
	key (streetID)
) engine=MyISAM;
