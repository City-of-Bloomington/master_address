-- @copyright 2017 City of Bloomington, Indiana
-- @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
set search_path = address;

-- Historically, used for user accounts.
-- Will probably merge the contacts table into here.
-- Contacts are people, too!
create table people (
	id        serial primary key,
	firstname varchar(128) not null,
	lastname  varchar(128) not null,
	email     varchar(255) not null,
	username  varchar(40) unique,
	password  varchar(40),
	role      varchar(30),
	authenticationMethod varchar(40)
);

--
-- Lookup Tables
--
create table quarterSections (
    code char(2) not null primary key
);

create table zipCodes (
    zip   integer     not null primary key,
    city  varchar(20) not null,
    state char(2)     not null default 'IN'
);

create table trashDays (
    id   smallserial primary key,
    name varchar(16) not null
);

create table recycleWeeks (
    code char(1) not null primary key
);

create table towns (
    id    serial      primary key,
    name  varchar(40) not null,
    code  varchar(9)  not null
);

create table townships (
    id           serial      primary key,
    name         varchar(40) not null,
    code         char(2)     not null,
    quarterCode  char(1)
);


create table jurisdictions (
    id   serial      primary key,
    name varchar(20) not null
);

create table directions (
    id   serial     primary key,
    name varchar(8) not null,
    code char(2)    not null
);

create table addressStatuses (
    id   serial      primary key,
    name varchar(16) not null
);

create table contactStatuses (
    id   serial      primary key,
    name varchar(16) not null
);

create table streetStatuses (
    id   serial      primary key,
    name varchar(16) not null
);

create table streetTypes (
    id   serial      primary key,
    code varchar(8)  not null unique,
    name varchar(16) not null
);

create table subunitTypes (
    id   serial      primary key,
    code varchar(8)  not null unique,
    name varchar(16) not null
);

create table locationTypes (
    id          serial       primary key,
    code        varchar(8)   not null unique,
    name        varchar(16)  not null unique,
    description varchar(128) not null
);

create table locationPurposes (
    id   serial       primary key,
    name varchar(128) not null,
    type varchar(32)  not null
);



--
-- Core tables
--


create table plats (
	id          serial primary key,
	platType    char(1),
	name        varchar(120) not null,
	cabinet     varchar(5),
	envelope    varchar(10),
	notes       varchar(240),
	township_id integer,
	startDate   date,
	endDate     date,
	foreign key (township_id) references townships(id)
);


create table subdivisions (
    id          serial primary key,
    township_id integer,
    foreign key (township_id) references townships(id)
);

-- Dropped date fields
-- startDate and endDate were null for all Oracle records
create table subdivisionNames (
    id             serial       primary key,
	subdivision_id integer      not null,
	name           varchar(100) not null,
	phase          integer,
	status         varchar(8)   not null,
	foreign key (subdivision_id) references subdivisions(id)
);

-- mast_street_name_type_master
create table streetNameTypes (
    id          serial      primary key,
    name        varchar(16) not null unique,
    description varchar(64) not null
);

create table streets (
	id        serial  primary key,
	town_id   integer,
	status_id integer not null,
	notes     varchar(240),
	foreign key (town_id  ) references towns         (id),
	foreign key (status_id) references streetStatuses(id)
);

create table streetNames (
    id               serial primary key,
    direction_id     integer,
    name             varchar(64),
    type_id          integer,
    postDirection_id integer,
    notes            varchar(240),
    foreign key (type_id         ) references streetTypes(id),
    foreign key (direction_id    ) references directions (id),
    foreign key (postDirection_id) references directions (id)
);

create table street_streetNames (
    street_id     integer,
    streetName_id integer,
    type_id       integer,
    startDate     date,
    endDate       date,
    rank          smallint,
    foreign key (street_id    ) references streets        (id),
    foreign key (streetName_id) references streetNames    (id),
    foreign key (type_id      ) references streetNameTypes(id)
);

create table street_townships (
    street_id   integer,
    township_id integer,
    foreign key (street_id  ) references streets  (id),
    foreign key (township_id) references townships(id)
);


create table street_subdivisions (
    street_id      integer,
    subdivision_id integer,
    foreign key (street_id     ) references streets     (id),
    foreign key (subdivision_id) references subdivisions(id)
);

create table addresses (
    id                 serial primary key,
    streetNumberPrefix varchar(8),
    streetNumber       integer not null,
    streetNumberSuffix varchar(8),
    adddress2          varchar(64),
    addressType        varchar(16) not null,
    street_id          integer not null,
    jurisdiction_id    integer not null,
    township_id        integer,
    subdivision_id     integer,
    plat_id            integer,
    section            varchar(16),
    quarterSection     char(2),
    plat_lotNumber     varchar(16),
    city               varchar(32),
    state              char(2) not null default 'IN',
    zip                integer,
    zipplus4           smallint,
    statePlaneX        integer,
    statePlaneY        integer,
    latitude           decimal(10, 8),
    longitude          decimal(10, 8),
    usng               varchar(20),
    geom public.geometry(Point, 2966),
    foreign key (street_id      ) references streets      (id),
    foreign key (jurisdiction_id) references jurisdictions(id),
    foreign key (township_id    ) references townships    (id),
    foreign key (subdivision_id ) references subdivisions (id),
    foreign key (plat_id        ) references plats        (id),
    foreign key (quarterSection ) references quarterSections(code),
    foreign key (zip            ) references zipCodes(zip)
);

create table address_status (
    id         serial  primary key,
    address_id integer not null,
    status_id  integer not null,
    startDate  date,
    endDate    date,
    foreign key (address_id) references addresses      (id),
    foreign key (status_id ) references addressStatuses(id)
);

create table subunits (
    id          serial  primary key,
    address_id  integer not null,
    type_id     integer,
    identifier  varchar(16),
    notes       varchar(240),
    statePlaneX integer,
    statePlaneY integer,
    latitude    decimal(10, 8),
    longitude   decimal(10, 8),
    usng        varchar(20),
    geom public.geometry(Point, 2966),
    foreign key (address_id) references addresses   (id),
    foreign key (type_id   ) references subunitTypes(id)
);

create table subunit_status (
    id         serial  primary key,
    subunit_id integer not null,
    status_id  integer not null,
    startDate  date,
    endDate    date,
    foreign key (subunit_id) references subunits       (id),
    foreign key (status_id ) references addressStatuses(id)
);

create table locations (
	id         serial  primary key,
	type_id    integer not null,
	address_id integer,
	subunit_id integer,
	mailable   boolean,
	occupiable boolean,
	active     boolean,
	unique (id, address_id, subunit_id),
	foreign key (address_id) references addresses    (id),
	foreign key (subunit_id) references subunits     (id),
	foreign key (type_id   ) references locationTypes(id)
);

create table location_status (
	id          serial  primary key,
	location_id integer not null,
	status_id   integer not null,
	startDate   date,
	endDate     date,
	foreign key (location_id) references locations      (id),
	foreign key (status_id  ) references addressStatuses(id)
);

create table sanitation (
    id               serial   primary key,
    location_id      integer  not null,
    trashDay_id      smallint,
    recycleWeek_code char(1),
    foreign key (location_id) references locations(id),
    foreign key (trashDay_id) references trashDays(id),
    foreign key (recycleWeek_code) references recycleWeeks(code)
);


-- mast_addr_assignment_contacts
-- Removed address, city, state, and zip fields
-- All rows in Oracle were null
-- This table will probably get merged into People
create table contacts (
	id           serial      primary key,
	status_id    integer     not null,
	contactType  varchar(20),
	lastname     varchar(30),
	firstname    varchar(20),
	phone        varchar(12),
	agency       varchar(40),
	email        varchar(128),
	notification boolean,
	coordination boolean,
	foreign key (status_id) references contactStatuses(id)
);

create table addressAssignmentLog (
    id          serial      primary key,
	address_id  integer     not null,
	location_id integer     not null,
	subunit_id  integer,
	contact_id  integer,
	actionDate  date        not null default CURRENT_DATE,
	action      varchar(20) not null,
	notes       varchar(240),
	foreign key (address_id ) references addresses(id),
	foreign key (location_id) references locations(id),
	foreign key (subunit_id ) references subunits (id),
	foreign key (contact_id ) references contacts (id)
);

create table addressChangeLog (
    id         serial      primary key,
	address_id integer     not null,
	person_id  integer     not null,
	contact_id integer,
	actionDate date        not null default CURRENT_DATE,
	action     varchar(20) not null,
	notes      varchar(255),
	foreign key (address_id) references addresses(id),
	foreign key (person_id ) references people   (id),
	foreign key (contact_id) references contacts (id)
);

create table locationChangeLog (
    id          serial  primary key,
    location_id integer not null,
    actionDate  date    not null default CURRENT_DATE,
    notes       varchar(240),
    foreign key (location_id) references locations(id)
);

create table streetChangeLog (
    id          serial      primary key,
	street_id   integer     not null,
	person_id   integer     not null,
	contact_id  integer,
	actionDate  date        not null default CURRENT_DATE,
	action      varchar(20) not null,
	notes       varchar(255),
	foreign key (street_id)  references streets (id),
	foreign key (person_id)  references people  (id),
	foreign key (contact_id) references contacts(id)
);

create table subunitChangeLog (
    id          serial      primary key,
	subunit_id  integer     not null,
	person_id   integer     not null,
	contact_id  integer,
	actionDate  date        not null default CURRENT_DATE,
	action      varchar(20) not null,
	notes       varchar(255),
	foreign key (subunit_id) references subunits(id),
	foreign key (person_id)  references people  (id),
	foreign key (contact_id) references contacts(id)
);
