-- @copyright 2017 City of Bloomington, Indiana
-- @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
set search_path = address;

--
-- Lookup Tables
--
create type quarter_sections as enum('NE', 'NW', 'SE', 'SW');

create table zip_codes (
    id    serial primary key,
    zip   integer     not null unique,
    city  varchar(20) not null,
    state char(2)     not null default 'IN'
);

create type trash_day    as enum('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday');
create type recycle_week as enum('A', 'B');

create table towns (
    id    serial      primary key,
    name  varchar(40) not null unique,
    code  varchar(9)  not null unique
);

create table townships (
    id            serial      primary key,
    name          varchar(40) not null,
    code          char(2)     not null,
    quarter_code  char(1)
);


create table jurisdictions (
    id   serial      primary key,
    name varchar(20) not null unique
);

create type directions       as enum('N', 'E', 'S', 'W');
create type address_statuses as enum('current', 'retired', 'proposed', 'duplicate', 'temporary');
create type  street_statuses as enum('current', 'retired', 'proposed', 'corrected');

create table street_types (
    id   serial      primary key,
    code varchar(8)  not null unique,
    name varchar(16) not null unique
);

create table subunit_types (
    id   serial      primary key,
    code varchar(8)  not null unique,
    name varchar(16) not null unique
);

create table location_types (
    id          serial       primary key,
    code        varchar(8)   not null unique,
    name        varchar(40)  not null unique,
    description varchar(128) not null
);

create table location_purposes (
    id           serial       primary key,
    name         varchar(128) not null,
    purpose_type varchar(32)  not null
);



--
-- Core tables
--


create table plats (
	id           serial primary key,
	plat_type    char(1),
	name         varchar(120) not null,
	cabinet      varchar(5),
	envelope     varchar(10),
	notes        varchar(240),
	township_id  integer,
	start_date   date,
	end_date     date,
	foreign key (township_id) references townships(id)
);


-- Combined subvisions and subdivisionNames into a single table
-- Subdivision names never changed
-- Dropped date fields
-- start_date and end_date were null for all Oracle records
create table subdivisions (
    id          serial primary key,
    township_id integer,
	name        varchar(100) not null,
	phase       integer,
	status      varchar(8)   not null,
    foreign key (township_id) references townships(id)
);

-- mast_street_name_type_master
create table street_name_types (
    id          serial      primary key,
    name        varchar(16) not null unique,
    description varchar(64) not null
);

create table streets (
	id        serial  primary key,
	town_id   integer,
	status    street_statuses not null,
	notes     varchar(240),
	foreign key (town_id  ) references towns(id)
);

create table street_names (
    id                serial      primary key,
    direction         directions,
    name              varchar(64) not null,
    post_direction    directions,
    suffix_code_id    integer,
    notes             varchar(240),
    foreign key (suffix_code_id) references street_types(id)
);

create table street_street_names (
    id             serial primary key,
    street_id      int    not null,
    street_name_id int    not null,
    type_id        int    not null,
    start_date     date,
    end_date       date,
    rank           smallint,
    foreign key (street_id     ) references streets          (id),
    foreign key (street_name_id) references street_names     (id),
    foreign key (type_id       ) references street_name_types(id)
);

create table state_roads (
    id           serial primary key,
    description  varchar(40) not null,
    abbreviation varchar(15) not null,
    number       varchar(10) not null
);

create table street_state_roads (
    street_id     int not null,
    state_road_id int not null,
    foreign key (street_id    ) references streets    (id),
    foreign key (state_road_id) references state_roads(id)
);

create table addresses (
    id                   serial primary key,
    street_number_prefix varchar(8),
    street_number        integer not null,
    street_number_suffix varchar(8),
    adddress2            varchar(64),
    address_type         varchar(16) not null,
    street_id            integer not null,
    jurisdiction_id      integer not null,
    township_id          integer,
    subdivision_id       integer,
    plat_id              integer,
    section              varchar(16),
    quarter_section      quarter_sections,
    plat_lot_number      varchar(16),
    city                 varchar(32),
    state                char(2) not null default 'IN',
    zip                  integer,
    zipplus4             smallint,
    state_plane_x        integer,
    state_plane_y        integer,
    latitude             decimal(10, 8),
    longitude            decimal(10, 8),
    usng                 varchar(20),
    notes                varchar(240),
    geom public.geometry(Point, 2966),
    foreign key (street_id        ) references streets      (id),
    foreign key (jurisdiction_id  ) references jurisdictions(id),
    foreign key (township_id      ) references townships    (id),
    foreign key (subdivision_id   ) references subdivisions (id),
    foreign key (plat_id          ) references plats        (id),
    foreign key (zip              ) references zip_codes(zip)
);
create index on addresses using gist(geom);
create trigger update_geom BEFORE INSERT OR UPDATE OF latitude, longitude ON addresses FOR EACH ROW EXECUTE PROCEDURE public.trig_set_geom();

create table address_status (
    id          serial           primary key,
    address_id  integer          not null,
    status      address_statuses not null,
    start_date  date,
    end_date    date,
    foreign key (address_id) references addresses(id)
);

create table subunits (
    id            serial  primary key,
    address_id    integer not null,
    type_id       integer,
    identifier    varchar(16),
    notes         varchar(240),
    state_plane_x integer,
    state_plane_y integer,
    latitude      decimal(10, 8),
    longitude     decimal(10, 8),
    usng          varchar(20),
    geom public.geometry(Point, 2966),
    foreign key (address_id) references addresses    (id),
    foreign key (type_id   ) references subunit_types(id)
);
create index on subunits using gist(geom);
create trigger update_geom BEFORE INSERT OR UPDATE OF latitude, longitude ON subunits FOR EACH ROW EXECUTE PROCEDURE public.trig_set_geom();

create table subunit_status (
    id          serial           primary key,
    subunit_id  integer          not null,
    status      address_statuses not null,
    start_date  date,
    end_date    date,
    foreign key (subunit_id) references subunits(id)
);

-- The primary key for locations is a composite.
-- We did this to avoid confusion in the common scenario.
-- Most locations have a single row and people think of
-- the location_id as key field.
-- However, locations that get readdressed, over time, will have the
-- same location_id across multiple rows.
-- This is a many to many relationship between addresses and locations.
-- We use address and subunit tables as the primary entrance to this table
create table locations (
	location_id serial,
	type_id     integer not null,
	address_id  integer,
	subunit_id  integer,
	mailable    boolean,
	occupiable  boolean,
	active      boolean,
    trash_day    trash_day,
    recycle_week recycle_week,
	unique (location_id, address_id, subunit_id),
	foreign key (address_id) references addresses     (id),
	foreign key (subunit_id) references subunits      (id),
	foreign key (type_id   ) references location_types(id)
);
create index on locations(location_id);

create table location_status (
	id           serial           primary key,
	location_id  integer          not null,
	status       address_statuses not null,
	start_date   date,
	end_date     date
);

-- mast_addr_assignment_contacts
-- Removed address, city, state, and zip fields
-- All rows in Oracle were null
-- Merged people into this table
create table people (
	id            serial      primary key,
	contact_type  varchar(20),
	firstname     varchar(20) not null,
	lastname      varchar(30) not null,
	email         varchar(128),
	phone         varchar(12),
	agency        varchar(40),
	current       boolean     not null default true,
	notification  boolean,
	coordination  boolean,
	username      varchar(40) unique,
	password      varchar(40),
	role          varchar(30),
	authentication_method varchar(40)
);

create table address_assignment_log (
    id           serial      primary key,
	address_id   integer     not null,
	location_id  integer     not null,
	subunit_id   integer,
	person_id    integer,
	contact_id   integer,
	action_date  date        not null default CURRENT_DATE,
	action       varchar(20) not null,
	notes        varchar(240),
	foreign key (address_id) references addresses(id),
	foreign key (subunit_id) references subunits (id),
	foreign key (person_id ) references people   (id),
	foreign key (contact_id) references people   (id)
);

create table address_change_log (
    id          serial      primary key,
	address_id  integer     not null,
	person_id   integer     not null,
	contact_id  integer,
	action_date date        not null default CURRENT_DATE,
	action      varchar(20) not null,
	notes       varchar(255),
	foreign key (address_id) references addresses(id),
	foreign key (person_id ) references people   (id),
	foreign key (contact_id) references people   (id)
);

create table location_change_log (
    id              serial  primary key,
    location_id     integer not null,
    old_location_id integer not null,
    person_id       integer,
    contact_id      integer,
    action_date     date    not null default CURRENT_DATE,
    action          varchar(20),
    notes           varchar(240),
	foreign key (person_id ) references people(id),
	foreign key (contact_id) references people(id)
);

create table street_change_log (
    id           serial      primary key,
	street_id    integer     not null,
	person_id    integer     not null,
	contact_id   integer,
	action_date  date        not null default CURRENT_DATE,
	action       varchar(20) not null,
	notes        varchar(255),
	foreign key (street_id)  references streets (id),
	foreign key (person_id)  references people  (id),
	foreign key (contact_id) references people  (id)
);

create table subunit_change_log (
    id           serial      primary key,
	subunit_id   integer     not null,
	person_id    integer     not null,
	contact_id   integer,
	action_date  date        not null default CURRENT_DATE,
	action       varchar(20) not null,
	notes        varchar(255),
	foreign key (subunit_id) references subunits(id),
	foreign key (person_id)  references people  (id),
	foreign key (contact_id) references people  (id)
);
