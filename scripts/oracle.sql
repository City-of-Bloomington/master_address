create table people (
	id number primary key,
	firstname varchar2(128) not null,
	lastname varchar2(128) not null,
	email varchar2(255) not null
);

create sequence people_id_seq nocache;

create trigger people_autoincrement_trigger
before insert on people
for each row
begin
select people_id_seq.nextval INTO :new.id from dual;
end;
/

create table users (
	id number primary key,
	person_id number not null unique,
	username varchar2(30) not null unique,
	password varchar2(32),
	authenticationmethod varchar2(40) default 'LDAP' not null,
	foreign key (person_id) references people(id)
);

create sequence users_id_seq nocache;

create trigger users_autoincrement_trigger
before insert on users
for each row
begin
select users_id_seq.nextval into :new.id from dual;
end;
/


create table roles (
	id number primary key,
	name varchar(30) not null unique
);

create sequence roles_id_seq nocache;

create trigger roles_autoincrement_trigger
before insert on roles
for each row
begin
select roles_id_seq.nextval into :new.id from dual;
end;
/

create table user_roles (
	user_id number not null,
	role_id number not null,
	primary key (user_id,role_id),
	foreign key(user_id) references users (id),
	foreign key(role_id) references roles (id)
);


create table towns_master (
	town_id number not null primary key,
	description varchar2(40),
	town_code varchar2(9)
);

create sequence town_id_s nocache;

create trigger towns_autoincrement_trigger
before insert on towns_master
for each row
begin
select town_id_s.nextval into :new.town_id from dual;
end;
/

create table township_master (
	township_id number not null primary key,
	name varchar2(40),
	township_abbreviation char(2),
	quarter_code char(1)
);

create sequence township_id_s nocache;

create trigger township_autoincrement_trigger
before insert on township_master
for each row
begin
select township_id_s.nextval into :new.township_id from dual;
end;
/

create table plat_master (
	plat_id number not null primary key,
	name varchar2(120),
	township_id number,
	effective_start_date date,
	effective_end_date date,
	plat_type char(1),
	plat_cabinet varchar2(5),
	envelope varchar2(10),
	notes varchar2(240),
	foreign key (township_id) references townships_master(township_id)
);

create sequence plat_id_s nocache;

create trigger plat_autoincrement_trigger
before insert on plat_master
for each row
begin
select plat_id_s.nextval into :new.plat_id from dual;
end;
/


create table voting_precincts (
	precinct varchar2(6) not null primary key,
	precinct_name varchar2(20),
	active char(1) not null
);

create table governmental_jurisdiction_mast (
	gov_jur_id number not null primary key,
	description varchar2(20)
);

create sequence gov_jur_id_s nocache;

create trigger gov_jur_trigger
before insert on governmental_jurisdiction_mast
for each row
begin
select gov_jur_id_s.nextval into :new.gov_jur_id from dual;
end;
/

create table building_types_master (
	building_type_id number not null primary key,
	description varchar2(20) not null
);

create sequence building_type_id_s nocache;

create trigger building_type_trigger
before insert on building_types_master
for each row
begin
select building_type_id_s.nextval into :new.building_type_id from dual;
end;
/

create table buildings_status_lookup (
	status_code number(3,0) not null primary key,
	description varchar2(240) not null
);

create table mast_street_direction_master (
	direction_code char(2) not null primary key,
	description varchar2(12) not null
);
