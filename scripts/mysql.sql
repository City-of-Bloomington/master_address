-- @copyright 2006-2009 City of Bloomington, Indiana
-- @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
-- @author Cliff Ingham <inghamn@bloomington.in.gov>
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
	town_id int unsigned not null primary key autoincrement,
	description varchar(40),
	town_code varchar(9)
);

create table township_master (
	township_id int unsigned not null primary key autoincrement,
	name varchar(40),
	township_abbreviation char(2),
	quarter_code char(1)
);

create table plat_master (
	plat_id int unsigned not null primary key autoincrement,
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
	precinct varchar(6) not null primary key,
	precinct_name varchar(20),
	active char(1) not null
);

create table state_road_master (
	state_road_id int unsigned not null primary key autoincrement,
	description varchar(40),
	abbreviation varchar(10)
);

create table addr_jurisdiction_master (
	jurisdiction_id int unsigned not null primary key,
	description varchar(20)
);
