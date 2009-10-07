-- We can add these directly to the Master_address schema.
-- There is no reason to put these into ENG anymore.
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

REM INSERTING into PEOPLE
Insert into PEOPLE (ID,FIRSTNAME,LASTNAME,EMAIL) values (1,'Cliff','Ingham','inghamn@bloomington.in.gov');
Insert into PEOPLE (ID,FIRSTNAME,LASTNAME,EMAIL) values (2,'Walid','Sibo','sibow@bloomington.in.gov');
Insert into PEOPLE (ID,FIRSTNAME,LASTNAME,EMAIL) values (6,'Adam','Williams','williama@bloomington.in.gov');
Insert into PEOPLE (ID,FIRSTNAME,LASTNAME,EMAIL) values (3,'Laura','Haley','haleyl@bloomington.in.gov');
Insert into PEOPLE (ID,FIRSTNAME,LASTNAME,EMAIL) values (4,'Alan','Schertz','schertza@bloomington.in.gov');
Insert into PEOPLE (ID,FIRSTNAME,LASTNAME,EMAIL) values (5,'Emily','Brown','browne@bloomington.in.gov');

REM INSERTING into USERS
Insert into USERS (ID,PERSON_ID,USERNAME,PASSWORD,AUTHENTICATIONMETHOD) values (1,1,'inghamn',null,'LDAP');
Insert into USERS (ID,PERSON_ID,USERNAME,PASSWORD,AUTHENTICATIONMETHOD) values (2,2,'sibow',null,'LDAP');
Insert into USERS (ID,PERSON_ID,USERNAME,PASSWORD,AUTHENTICATIONMETHOD) values (6,6,'williama',null,'LDAP');
Insert into USERS (ID,PERSON_ID,USERNAME,PASSWORD,AUTHENTICATIONMETHOD) values (3,3,'haleyl',null,'LDAP');
Insert into USERS (ID,PERSON_ID,USERNAME,PASSWORD,AUTHENTICATIONMETHOD) values (4,4,'schertza',null,'LDAP');
Insert into USERS (ID,PERSON_ID,USERNAME,PASSWORD,AUTHENTICATIONMETHOD) values (5,5,'browne',null,'LDAP');

REM INSERTING into ROLES
Insert into ROLES (ID,NAME) values (1,'Administrator');
Insert into ROLES (ID,NAME) values (2,'Engineering');
Insert into ROLES (ID,NAME) values (3,'GIS');

REM INSERTING into USER_ROLES
Insert into USER_ROLES (USER_ID,ROLE_ID) values (1,1);
Insert into USER_ROLES (USER_ID,ROLE_ID) values (2,1);
Insert into USER_ROLES (USER_ID,ROLE_ID) values (3,1);
Insert into USER_ROLES (USER_ID,ROLE_ID) values (4,1);
Insert into USER_ROLES (USER_ID,ROLE_ID) values (5,1);
Insert into USER_ROLES (USER_ID,ROLE_ID) values (6,1);
