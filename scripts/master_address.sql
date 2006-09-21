CREATE TABLE towns (
	id int unsigned auto_increment NOT NULL,
	name varchar(30) UNIQUE NOT NULL,
	PRIMARY KEY(id)) engine=InnoDB;

CREATE TABLE routes (
	id int unsigned auto_increment NOT NULL,
	PRIMARY KEY(id)) engine=InnoDB;

CREATE TABLE subdivisions (
	id int unsigned auto_increment NOT NULL,
	PRIMARY KEY(id)) engine=InnoDB;

CREATE TABLE townships (
	id int unsigned auto_increment NOT NULL,
	name varchar(128) UNIQUE NOT NULL,
	abbreviation char(2) UNIQUE NOT NULL,
	quarterCode char(1),
	PRIMARY KEY(id)) engine=InnoDB;

CREATE TABLE jurisdictions (
	id int unsigned auto_increment NOT NULL,
	name varchar(128) UNIQUE NOT NULL,
	PRIMARY KEY(id)) engine=InnoDB;

CREATE TABLE directions (
	id int unsigned auto_increment NOT NULL,
	code char(2) UNIQUE NOT NULL,
	direction varchar(30) UNIQUE NOT NULL,
	PRIMARY KEY(id)) engine=InnoDB;

CREATE TABLE thoroughfareClasses (
	id int unsigned auto_increment NOT NULL,
	class varchar(5) NOT NULL,
	description varchar(128) NOT NULL,
	PRIMARY KEY(id));

CREATE TABLE travelWayCodes (
	id tinyint(1) unsigned NOT NULL,
	PRIMARY KEY(id));

CREATE TABLE placeStatuses (
	id int unsigned not null primary key auto_increment,
	status varchar(30) not null unique
) engine=InnoDB;

CREATE TABLE addressStatuses (
	id int unsigned not null primary key auto_increment,
	status varchar(30) not null unique
) engine=InnoDB;

CREATE TABLE buildingStatuses (
	id int unsigned not null primary key auto_increment,
	status varchar(30) not null unique
) engine=InnoDB;

CREATE TABLE unitStatuses (
	id int unsigned not null primary key auto_increment,
	status varchar(30) not null unique
) engine=InnoDB;

CREATE TABLE streetStatuses (
	id int unsigned not null primary key auto_increment,
	status varchar(30) not null unique
) engine=InnoDB;

CREATE TABLE intersectionStatuses (
	id int unsigned not null primary key auto_increment,
	status varchar(30) not null unique
) engine=InnoDB;

CREATE TABLE segmentStatuses (
	id int unsigned not null primary key auto_increment,
	status varchar(30) not null unique
) engine=InnoDB;

CREATE TABLE inventoryStatuses (
	id int unsigned not null primary key auto_increment,
	status varchar(30) not null unique
) engine=InnoDB;

CREATE TABLE constructionStatuses (
	id int unsigned not null primary key auto_increment,
	status varchar(30) not null unique
) engine=InnoDB;

CREATE TABLE mapStatuses (
	id int unsigned not null primary key auto_increment,
	status varchar(30) not null unique
) engine=InnoDB;

CREATE TABLE sidewalkStatuses (
	id int unsigned not null primary key auto_increment,
	status varchar(30) not null unique
) engine=InnoDB;


CREATE TABLE suffixes (
	id int unsigned auto_increment NOT NULL,
	suffix char(4) UNIQUE NOT NULL,
	description varchar(30) UNIQUE NOT NULL,
	PRIMARY KEY(id)) engine=InnoDB;

CREATE TABLE streetNameTypes (
	id int unsigned auto_increment NOT NULL,
	type varchar(30) UNIQUE NOT NULL,
	description varchar(128) UNIQUE NOT NULL,
	PRIMARY KEY(id)) engine=InnoDB;

CREATE TABLE districtTypes (
	id int unsigned auto_increment NOT NULL,
	type varchar(128) UNIQUE NOT NULL,
	PRIMARY KEY(id)) engine=InnoDB;

CREATE TABLE trashPickupDays (
	id int unsigned auto_increment NOT NULL,
	day enum('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday') DEFAULT 'Monday' UNIQUE NOT NULL,
	PRIMARY KEY(id)) engine=InnoDB;

CREATE TABLE recyclingPickupWeeks (
	id int unsigned auto_increment NOT NULL,
	week char(1) UNIQUE NOT NULL,
	PRIMARY KEY(id)) engine=InnoDB;

CREATE TABLE annexations (
	id int unsigned auto_increment NOT NULL,
	ordinanceNumber varchar(12) NOT NULL,
	name varchar(128),
	PRIMARY KEY(id)) engine=InnoDB;

CREATE TABLE platTypes (
	id int unsigned auto_increment NOT NULL,
	type char(1) UNIQUE NOT NULL,
	description varchar(128) UNIQUE NOT NULL,
	PRIMARY KEY(id)) engine=InnoDB;

CREATE TABLE placeTypes (
	id int unsigned auto_increment NOT NULL,
	type varchar(40) NOT NULL,
	description varchar(255) NOT NULL,
	PRIMARY KEY(id)) engine=InnoDB;

CREATE TABLE unitTypes (
	id int unsigned auto_increment NOT NULL,
	type varchar(10) NOT NULL,
	description varchar(128) NOT NULL,
	PRIMARY KEY(id)) engine=InnoDB;

CREATE TABLE buildingTypes (
	id int unsigned NOT NULL,
	description varchar(128) NOT NULL,
	PRIMARY KEY(id)) engine=InnoDB;

CREATE TABLE stateRoads (
	id int unsigned  NOT NULL,
	name varchar(128) NOT NULL,
	abbreviation varchar(15) NOT NULL,
	PRIMARY KEY(id)) engine=InnoDB;

CREATE TABLE cities (
	id int unsigned auto_increment NOT NULL,
	name varchar(30) UNIQUE,
	PRIMARY KEY(id)) engine=InnoDB;

CREATE TABLE plats (
	id int unsigned auto_increment NOT NULL,
	name varchar(128) NOT NULL,
	township_id int unsigned,
	platType_id int unsigned,
	cabinet varchar(30),
	envelope varchar(30),
	notes varchar(255),
	PRIMARY KEY(id),
	FOREIGN KEY(township_id) REFERENCES townships (id),
	FOREIGN KEY(platType_id) REFERENCES platTypes (id)) engine=InnoDB;

CREATE TABLE users (
	id int unsigned auto_increment NOT NULL,
	username varchar(30) UNIQUE,
	password varchar(32),
	authenticationMethod varchar(40) DEFAULT 'LDAP' NOT NULL,
	firstname varchar(128) NOT NULL,
	lastname varchar(128) NOT NULL,
	department varchar(128),
	phone varchar(50),
	PRIMARY KEY(id)) engine=InnoDB;

CREATE TABLE roles (
	id int unsigned auto_increment NOT NULL,
	role varchar(30) UNIQUE NOT NULL,
	PRIMARY KEY(id)) engine=InnoDB;

CREATE TABLE names (
	id int unsigned auto_increment NOT NULL,
	town_id int unsigned,
	direction_id int unsigned,
	name varchar(128) NOT NULL,
	suffix_id int unsigned,
	postDirection_id int unsigned,
	startDate date,
	endDate date,
	notes varchar(255),
	PRIMARY KEY(id),
	FOREIGN KEY(town_id) REFERENCES towns (id),
	FOREIGN KEY(suffix_id) REFERENCES suffixes (id),
	FOREIGN KEY(direction_id) REFERENCES directions (id),
	FOREIGN KEY(postDirection_id) REFERENCES directions (id)) engine=InnoDB;

CREATE TABLE streets (
	id int unsigned auto_increment NOT NULL,
	notes varchar(255),
	streetStatus_id int unsigned DEFAULT 1 NOT NULL,
	PRIMARY KEY(id),
	FOREIGN KEY(streetStatus_id) REFERENCES streetStatuses (id)) engine=InnoDB;

CREATE TABLE intersections (
	id int unsigned auto_increment NOT NULL,
	tag varchar(8) not null,
	name varchar(128),
	intersectionStatus_id int unsigned,
	jurisdiction_id int unsigned,
	x int(7) unsigned,
	y int(7) unsigned,
	notes text,
	PRIMARY KEY(id),
	unique (tag),
	FOREIGN KEY(jurisdiction_id) REFERENCES jurisdictions (id),
	FOREIGN KEY(intersectionStatus_id) REFERENCES intersectionStatuses (id)) engine=InnoDB;

CREATE TABLE segments (
	id int unsigned auto_increment NOT NULL,
	tag varchar(8) NOT NULL,
	segmentStatus_id int unsigned DEFAULT 1 NOT NULL,
	dataOwner_id int unsigned,
	description varchar(255),
	station int unsigned,
	lowAddressNumber int(4) unsigned NOT NULL,
	highAddressNumber int(4) unsigned NOT NULL,
	leftLowAddressNumber int(4) unsigned NOT NULL,
	leftHighAddressNumber int(4) unsigned NOT NULL,
	rightLowAddressNumber int(4) unsigned NOT NULL,
	rightHighAddressNumber int(4) unsigned NOT NULL,
	jurisdiction_id int unsigned NOT NULL,
	jurisdictionLeft_id int unsigned,
	jurisdictionRight_id int unsigned,
	constructionStatus_id int unsigned DEFAULT 1 NOT NULL,
	dedicatedRightOfWay enum('Y','N'),
	maintainedBy enum('UNKNOWN','STATE','COUNTY','PRIVATE','DEVELOPER','BLOOMINGTON','IU') DEFAULT 'UNKNOWN' NOT NULL,
	inventoryStatus_id int unsigned,
	travelWayCode_id tinyint(1) unsigned,
	travelDirection_id int unsigned,
	speedLimit tinyint(2) unsigned,
	speedLimitOrdinance varchar(25),
	thoroughfareClass_id int unsigned,
	segmentBack_id int unsigned,
	segmentAhead_id int unsigned,
	intersectionBack_id int unsigned,
	intersectionAhead_id int unsigned,
	lowAddressIntersection_id int unsigned,
	highAddressIntersection_id int unsigned,
	leftSidewalkStatus_id int unsigned,
	rightSidewalkStatus_id int unsigned,
	mapArea char(3),
	mapStatus_id int unsigned,
	mapSource varchar(50),
	labelX int(7) unsigned,
	labelY int(7) unsigned,
	notes text,
	PRIMARY KEY(id),
	unique (tag),
	FOREIGN KEY(dataOwner_id) REFERENCES jurisdictions (id),
	FOREIGN KEY(segmentStatus_id) REFERENCES segmentStatuses (id),
	FOREIGN KEY(jurisdiction_id) REFERENCES jurisdictions (id),
	FOREIGN KEY(jurisdictionLeft_id) REFERENCES jurisdictions (id),
	FOREIGN KEY(jurisdictionRight_id) REFERENCES jurisdictions (id),
	FOREIGN KEY(constructionStatus_id) REFERENCES constructionStatuses (id),
	FOREIGN KEY(inventoryStatus_id) REFERENCES inventoryStatuses (id),
	FOREIGN KEY(thoroughfareClass_id) REFERENCES thoroughfareClasses (id),
	FOREIGN KEY(travelDirection_id) REFERENCES directions (id),
	FOREIGN KEY(travelWayCode_id) REFERENCES travelWayCodes (id),
	FOREIGN KEY(mapStatus_id) REFERENCES mapStatuses (id),
	FOREIGN KEY(intersectionAhead_id) REFERENCES intersections (id),
	FOREIGN KEY(intersectionBack_id) REFERENCES intersections (id),
	FOREIGN KEY(lowAddressIntersection_id) REFERENCES intersections (id),
	FOREIGN KEY(highAddressIntersection_id) REFERENCES intersections (id)) engine=InnoDB;

CREATE TABLE places (
	id int unsigned auto_increment NOT NULL,
	name varchar(128),
	township_id int unsigned,
	jurisdiction_id int unsigned NOT NULL,
	trashPickupDay_id int unsigned,
	recyclingPickupWeek_id int unsigned,
	mailable tinyint(1) unsigned,
	livable tinyint(1) unsigned,
	section varchar(10),
	quarterSection enum('NE','NW','SE','SW'),
	class varchar(30),
	placeType_id int unsigned,
	censusBlockFIPSCode varchar(20),
	statePlaneX int(7) unsigned,
	statePlaneY int(7) unsigned,
	latitude float(10,6),
	longitude float(10,6),
	startDate date,
	endDate date,
	placeStatus_id int unsigned NOT NULL default 1,
	plat_id int unsigned,
	lotNumber int unsigned,
	PRIMARY KEY(id),
	FOREIGN KEY(township_id) REFERENCES townships (id),
	FOREIGN KEY(jurisdiction_id) REFERENCES jurisdictions (id),
	FOREIGN KEY(trashPickupDay_id) REFERENCES trashPickupDays (id),
	FOREIGN KEY(recyclingPickupWeek_id) REFERENCES recyclingPickupWeeks (id),
	FOREIGN KEY(placeType_id) REFERENCES placeTypes (id),
	FOREIGN KEY(placeStatus_id) REFERENCES placeStatuses (id),
	FOREIGN KEY(plat_id) REFERENCES plats (id)) engine=InnoDB;

CREATE TABLE buildings (
	id int unsigned auto_increment NOT NULL,
	name varchar(128),
	startDate date NOT NULL,
	endDate date,
	GISTag varchar(20),
	buildingStatus_id int unsigned DEFAULT 1 NOT NULL,
	PRIMARY KEY(id),
	FOREIGN KEY(buildingStatus_id) REFERENCES buildingStatuses (id)) engine=InnoDB;

CREATE TABLE units (
	id int unsigned auto_increment NOT NULL,
	place_id int unsigned NOT NULL,
	unitType_id int unsigned NOT NULL,
	building_id int unsigned,
	identifier varchar(30),
	mailable tinyint(1) unsigned,
	livable tinyint(1) unsigned,
	notes varchar(255),
	unitStatus_id int unsigned,
	PRIMARY KEY(id),
	FOREIGN KEY(building_id) REFERENCES buildings (id),
	FOREIGN KEY(unitType_id) REFERENCES unitTypes (id),
	FOREIGN KEY(place_id) REFERENCES places (id),
	FOREIGN KEY(unitStatus_id) REFERENCES unitStatuses (id)) engine=InnoDB;

CREATE TABLE addresses (
	id int unsigned auto_increment NOT NULL,
	place_id int unsigned NOT NULL,
	street_id int unsigned NOT NULL,
	segment_id int unsigned,
	number int unsigned NOT NULL,
	suffix char(5),
	addressType varchar(20) DEFAULT 'STREET' NOT NULL,
	city_id int unsigned NOT NULL,
	zip int(5) unsigned zerofill,
	zipplus4 int(4) unsigned zerofill,
	addressStatus_id int unsigned DEFAULT 1 NOT NULL,
	active enum('Y','N') DEFAULT 'Y' NOT NULL,
	startDate date NOT NULL,
	endDate date,
	notes varchar(255),
	PRIMARY KEY(id),
	FOREIGN KEY(place_id) REFERENCES places (id),
	FOREIGN KEY(street_id) REFERENCES streets (id),
	FOREIGN KEY(segment_id) REFERENCES segments (id),
	FOREIGN KEY(city_id) REFERENCES cities (id),
	FOREIGN KEY(addressStatus_id) REFERENCES addressStatuses (id)) engine=InnoDB;

CREATE TABLE streetNames (
	id int unsigned auto_increment NOT NULL,
	street_id int unsigned NOT NULL,
	name_id int unsigned NOT NULL,
	streetNameType_id int unsigned,
	primary key (id),
	FOREIGN KEY(street_id) REFERENCES streets (id),
	FOREIGN KEY(name_id) REFERENCES names (id),
	FOREIGN KEY(streetNameType_id) REFERENCES streetNameTypes (id)) engine=InnoDB;

CREATE TABLE route_streets (
	route_id int unsigned NOT NULL,
	street_id int unsigned NOT NULL,
	primary key (route_id,street_id),
	FOREIGN KEY(route_id) REFERENCES routes (id),
	FOREIGN KEY(street_id) REFERENCES streets (id)) engine=InnoDB;

CREATE TABLE subdivisionNames (
	subdivision_id int unsigned auto_increment NOT NULL,
	name varchar(128) NOT NULL,
	startDate date NOT NULL,
	endDate date,
	status enum('Current','Renamed') DEFAULT 'Current' NOT NULL,
	primary key (subdivision_id,name),
	FOREIGN KEY(subdivision_id) REFERENCES subdivisions (id)) engine=InnoDB;

CREATE TABLE subdivision_plats (
	subdivision_id int unsigned NOT NULL,
	plat_id int unsigned NOT NULL,
	startDate date NOT NULL,
	primary key (subdivision_id,plat_id),
	FOREIGN KEY(plat_id) REFERENCES plats (id),
	FOREIGN KEY(subdivision_id) REFERENCES subdivisions (id)) engine=InnoDB;

CREATE TABLE districts (
	id int unsigned auto_increment NOT NULL,
	name varchar(128) UNIQUE NOT NULL,
	districtType_id int unsigned NOT NULL,
	PRIMARY KEY(id),
	FOREIGN KEY(districtType_id) REFERENCES districtTypes (id)) engine=InnoDB;

CREATE TABLE district_places (
	place_id int unsigned NOT NULL,
	district_id int unsigned NOT NULL,
	primary key (place_id,district_id),
	FOREIGN KEY(district_id) REFERENCES places (id),
	FOREIGN KEY(district_id) REFERENCES districts (id)) engine=InnoDB;

CREATE TABLE placeHistory (
	id int unsigned auto_increment NOT NULL,
	place_id int unsigned NOT NULL,
	action varchar(30) NOT NULL,
	date date NOT NULL,
	notes varchar(255),
	user_id int unsigned,
	PRIMARY KEY(id),
	FOREIGN KEY(place_id) REFERENCES places (id),
	FOREIGN KEY(user_id) REFERENCES users (id)) engine=InnoDB;

CREATE TABLE building_places (
	building_id int unsigned NOT NULL,
	place_id int unsigned NOT NULL,
	primary key (building_id,place_id),
	FOREIGN KEY(building_id) REFERENCES buildings (id),
	FOREIGN KEY(place_id) REFERENCES places (id)) engine=InnoDB;

CREATE TABLE user_roles (
	user_id int unsigned NOT NULL,
	role_id int unsigned NOT NULL,
	primary key (user_id,role_id),
	FOREIGN KEY(user_id) REFERENCES users (id),
	FOREIGN KEY(role_id) REFERENCES roles (id)) engine=InnoDB;

create table street_segments (
	street_id int unsigned NOT NULl,
	segment_id int unsigned NOT NULL,
	primary key (street_id,segment_id),
	FOREIGN KEY(street_id) REFERENCES streets (id),
	FOREIGN KEY(segment_id) REFERENCES segments (id)) engine=InnoDB;

