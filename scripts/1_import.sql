----------------------------------------------------------------------------
-- DATA Cleanup
----------------------------------------------------------------------------
-- We need to make sure that all the street_numbers are valid before importing the data
-- Make sure that there's spaces after each number and before any letter or fraction
-- Also make sure that we don't have any numbers that aren't numbers.
select street_address_id,street_number,street_id from mast_address where street_number regexp '[a-zA-Z]';

-- Once we're done with the import, we're going to come back to this list
-- The places on this list should really be subunits.  But it'll be
-- easier to fix once we've got the data into the new schema.
select street_address_id,street_number,street_id from mast_address where street_number is null;

----------------------------------------------------------------------------
-- Target checkist
-- Data for these tables needs to imported in this order, to preserve
-- the correct foreign key relationships
----------------------------------------------------------------------------

-- routes
-- routeStreets
-- Actually, routes do not exist in the old data.  There's nothing to import yet

-- roles
insert roles (role) select distinct contact_type from oldAddressData.mast_addr_assignment_contact;
insert roles set role='Administrator';


-- users
insert users (id,firstname,lastname,phone,department)
select contact_id,first_name,last_name,phone_number,agency from oldAddressData.mast_addr_assignment_contact;

-- userRoles
insert user_roles select contact_id,id from oldAddressData.mast_addr_assignment_contact
left join roles on contact_type=role;

-- stateRoads
insert stateRoads select * from oldAddressData.state_road_master;

-- towns
insert towns select * from oldAddressData.towns_master;

-- townships
insert townships select * from oldAddressData.township_master;

-- jurisdictions
insert jurisdictions select * from oldAddressData.governmental_jurisdiction_mast;

-- cities
insert cities (name) select distinct city from oldAddressData.mast_address;

-- statusCodes
--		This is actually combining 3 tables of status codes into one.  The original
--		status_code integers won't be valid anymore.  When importing status fields
--		you will need to join those and compare the description with this statusCode.status
--		for the correct statusCode to use
-- We also need an UNKNOWN status for the ones that are blank
insert statuses values(1,"UNKNOWN");

insert statuses (status) (select description from oldAddressData.buildings_status_lookup)
union (select description from oldAddressData.mast_address_status_lookup)
union (select description from oldAddressData.mast_street_status_lookup)
order by description;

-- suffixes
insert suffixes (suffix,description) select * from oldAddressData.mast_street_type_suffix_master;

-- streetNameTypes
insert streetNameTypes (type,description) select * from oldAddressData.mast_street_name_type_master;

-- districtTypes
insert districtTypes (type) select distinct type from oldAddressData.addr_location_purpose_mast order by type;

-- placeTypes
insert placeTypes (type,description) select * from oldAddressData.addr_location_types_master;

-- unitTypes
insert unitTypes (type,description) select * from oldAddressData.mast_addr_subunit_types_mast;

-- buildingTypes
insert buildingTypes select * from oldAddressData.building_types_master;

-- trashPickupDays
insert trashPickupDays (day) select * from oldAddressData.trash_pickup_master;

-- recyclingPickupWeeks
insert recyclingPickupWeeks (week) select * from oldAddressData.trash_recycle_week_master;

-- directions
insert directions (code,direction) select * from oldAddressData.mast_street_direction_master;

-- annexations
insert annexations (ordinanceNumber,name) select ordinance_number,name from oldAddressData.annexations;

-- districts
insert districts select location_purpose_id,description,districtTypes.id
from oldAddressData.addr_location_purpose_mast left join districtTypes using (type);

-- platType
-- This data doesn't exist anywhere yet.
insert platTypes (type,description) values('S','Residential Subdivision');
insert platTypes (type,description) values('C','Commercial');
insert platTypes (type,description) values('A','Amended');

-- plats
insert plats select plat_id,name,township_id,platTypes.id,plat_cabinet,envelope,notes
from oldAddressData.plat_master left join platTypes on plat_type=type;



-- names
insert names (town_id,direction_id,name,suffix_id,postDirection_id,startDate,endDate,notes)
select distinct town_id,d.id,street_name,suffixes.id,p.id,effective_start_date,effective_end_date,mast_street_names.notes
from oldAddressData.mast_street_names left join oldAddressData.mast_street using (street_id)
left join directions d on mast_street_names.street_direction_code=d.code
left join suffixes on street_type_suffix_code=suffixes.suffix
left join directions p on mast_street_names.post_direction_suffix_code=p.code;


-- streets
insert streets select distinct streetID,notes,ifnull(s.id,1) from oldAddressData.segments
left join oldAddressData.mast_street on streetID=street_id
left join oldAddressData.mast_street_status_lookup using (status_code)
left join statuses s on description=s.status
where streetID>0 order by streetID;


-- segments
-- Segments have COUNTY in as a location, which should equate to Monroe County
update oldAddressData.segments set location='MONROE COUNTY' where location='COUNTY';

-- Clean up the travel_d information
update oldAddressData.segments set travel_d=null where travel_d not in ('N','S','E','W');

-- The segments have a BUILT status that hasn't been added to statusCodes yet
insert statuses set status='BUILT';

-- We need some usernames in the new data, so we can assign correct userIDs for the last updated fields
update users set username='haleyl' where firstname='LAURA' and lastname='HALEY';
update users set username='goodmanr' where firstname='RUSS' and lastname='GOODMAN';
insert users set username='winklec',authenticationMethod='LDAP',firstname='Chuck',lastname='Winkle',department='ITS',phone='812-349-3595';

-- Old Segments may have zeros instead of null for streetID
update oldAddressData.segments set streetID=null where streetID=0;


insert segments select null,streetID,tag,lowadd,highadd,j.id,s.id,comments,speed,indotID,class,maintain,
leftlow,lefthigh,rightlow,righthigh,rcinode1,rcinode2,low_node,high_node,
low_x,low_y,high_x,high_y,travel,d.id,complete,rclback,rclahead,class_row,
maparea,lastdate,up_act,u.id
from oldAddressData.segments left join jurisdictions j on location=j.name
left join directions d on travel_d=d.code
left join users u on up_by=username
left join statuses s on seg_stat=status;

alter table segments add index (tag);

-- Some final cleanup on segments
update segments set transportationClass=null where transportationClass="\'";
update segments set transportationClass=null where transportationClass="-";
update segments set transportationClass=null where transportationClass="0";



-- streetNames
-- You will need to run PHP -> importStreetNames.php at this point
-- I could not figure out a way to get it to work using just SQL.  It wasn't macthing the fields
-- in the new names table.




-- places
-- Run cleanLocations.php now  this will combine the locations that should really be the same place

-- We need to create some temp tables, that we can clean out as we import the data we want.
create table tempLocations like oldAddressData.address_location;
insert tempLocations select * from oldAddressData.address_location;
delete from tempLocations where subunit_id is not null;


insert places
select distinct l.location_id,common_name,township_id,gov_jur_id,t.id,g.id,r.id,mailable_flag,livable_flag,section,quarter_section,location_class,
census_block_fips_code,state_plane_x_coordinate,state_plane_y_coordinate,latitude,longitude
from tempLocations l left join oldAddressData.mast_address a using (street_address_id)
left join oldAddressData.mast_address_sanitation s using (street_address_id)
left join oldAddressData.locations_classes c on l.location_id=c.location_id
left join trashPickupDays t on trash_pickup_day=t.day
left join trashPickupDays g on large_item_pickup_day=g.day
left join recyclingPickupWeeks r on recycle_week=r.week
group by location_id;


-- placeHistory
-- There are some contacts that just aren't in the system anymore.  We should clear those contact_id's out
update oldAddressData.mast_address_assignment_hist set contact_id=null where contact_id not in (select id from users);

insert placeHistory select location_id,action,action_date,notes,contact_id from oldAddressData.mast_address_assignment_hist
where location_id in (select id from places);

-- units
-- Run PHP-> importUnits at this point.
-- We'll need to clean out the data that we imported, though
-- This should zero out tempLocations
delete from tempLocations where location_id in (select id from places);

-- Confirm that it's zero before dropping
select count(*) from tempLocations;
drop table tempLocations;


-- placeStatus
insert place_status
select p.id,effective_start_date,effective_end_date,s.id
from places p left join oldAddressData.mast_address_location_status on p.id=location_id
left join oldAddressData.mast_address_status_lookup using (status_code)
left join statuses s on description=status;


-- addresses
-- Now we can import all the addresses
-- run 1-4-importAddresses.php
-- Check the noSegmentData.txt file that this generates.  These will all be problem addresses


-- districtPlaces
insert district_places
select location_id,location_purpose_id from oldAddressData.addr_location_purposes
where location_id in (select id from places);


-- buildings
-- I think these are going to have to be on hold for now.  The data's just to wonky.
-- Right now, we can just link the units directly to the locations.  Come back later
-- and do the buildings

-- This would import the buildings, if we didn't have buildings that somehow existed on multiple locations
-- insert buildings
-- select t.building_id,l.placeID,building_name,effective_start_date,effective_end_date,gis_tag,statusCode
-- from places l left join oldAddressData.building_address_location on placeID=location_id
-- left join tempBuildings t using(building_id)
-- left join oldAddressData.buildings_status_lookup using (status_code)
-- left join statusCodes on description=status;


-- The rest of these tables don't have complete data in the old system.
-- I'm putting their import on hold for now.
-- subdivisions
-- subdivisionNames
-- segmentPlats

-------------------------------------------------------------------------------
-- Source checklist
-- Make a note on these tables when you have all the data accounted for in
-- the import section above
-------------------------------------------------------------------------------


-- IMPORTED	addr_location_purposes
-- IMPORTED	annexations
-- IMPORTED	segments
-- IMPORTED	address_location
-- IMPORTED	addr_location_purpose_mast
-- IMPORTED	addr_location_types_master
-- IMPORTED	buildings_status_lookup
-- IMPORTED	building_types_master
-- IMPORTED	governmental_jurisdiction_mast
-- IMPORTED	locations_classes
-- IMPORTED	mast_address
-- IMPORTED	mast_addr_assignment_contact
-- IMPORTED	mast_address_assignment_hist
-- IMPORTED	mast_address_location_status
-- IMPORTED	mast_addr_subunit_types_mast
-- IMPORTED	mast_address_location_change
-- IMPORTED	mast_address_sanitation
-- IMPORTED	mast_address_status
-- IMPORTED	mast_address_status_lookup
-- IMPORTED	mast_street
-- IMPORTED	mast_street_direction_master
-- IMPORTED	mast_street_names
-- IMPORTED	mast_street_name_type_master
-- IMPORTED mast_street_status_lookup
-- IMPORTED	mast_street_type_suffix_master
-- IMPORTED	plat_master
-- IMPORTED	state_road_master
-- IMPORTED	towns_master
-- IMPORTED	township_master
-- IMPORTED	trash_pickup_master
-- IMPORTED	trash_recycle_week_master
-- IMPORTED	mast_address_subunits

-- ON HOLD	building_address_location		Data needs to be cleaned up big time before we can
-- ON HOLD	buildings						bring these two tables in

-- ON HOLD	subdivision_master				We're missing a lot of data to be able to bring subdivions
-- ON HOLD	subdivision_names				in.  We need plats to come in first, for example.


-- IGNORED	mast_address_subunit_status		This table should really be the same data as the location status

-- IGNORED	mast_addr_assgn_hist_temp		Old temp table, data is not relevant
-- IGNORED	mast_address_parcel				Table has no data
-- IGNORED	addr_location_rel_types_mast	Table has no data
-- IGNORED	addr_location_relationships		Table has no data
-- IGNORED	addr_jurisdiction_master 		This table is fully contained in governamtal_jurisdiction_mast
-- IGNORED	mast_address_annexation			Table has no data
-- IGNORED	mast_street_addr_jurisdictions	Table has no data
-- IGNORED	mast_street_gov_jurisdictions	Table has no data
-- IGNORED	mast_street_plat				Table has no data
-- IGNORED	mast_street_section				Table has no data
-- IGNORED	mast_street_state_road			Table has no data
-- IGNORED	mast_street_subdivision			Table has no data
-- IGNORED	mast_street_townships			Table has no data
-- IGNORED	parcel							Table has no data
-- IGNORED	trash_large_item_pickup_master  This table is equivilent to trash_pickup_master
-- IGNORED	quarter_section_master			Just a lookup table with four directions


----------------------------------------------------------------------------
-- DATA Cleanup
----------------------------------------------------------------------------
select street_address_id,street_number,street_id from mast_address where street_number regexp '[a-zA-Z]';


----------------------------------------------------------------------------
-- Fulltext search index creation
----------------------------------------------------------------------------
create table addressIndex (
fullAddress varchar(255) not null,
address_id int unsigned not null,
segment_id int unsigned not null,
street_id int unsigned not null,
name_id int unsigned not null,
place_id int unsigned not null,
unit_id int unsigned,
fulltext (fullAddress),
foreign key (address_id) references addresses (id),
foreign key (segment_id) references segments (id),
foreign key (street_id) references streets (id),
foreign key (name_id) references names (id),
foreign key (place_id) references places (id),
foreign key (unit_id) references units (id)
) engine=MyISAM;


insert into addressIndex
select concat_ws(' ',addresses.number,d.code,names.name,x.suffix,p.code,unitTypes.type,units.identifier),
addresses.id as address_id,segments.id as segment_id,streets.id as street_id,names.id as name_id,addresses.place_id,units.id as unit_id
from addresses left join segments on addresses.segment_id=segments.id
left join streets on segments.street_id=streets.id
left join streetNames on streets.id=streetNames.street_id
left join names on streetNames.name_id=names.id
left join units on addresses.place_id=units.place_id
left join unitTypes on unitType_id=unitTypes.id
left join directions d on names.direction_id=d.id
left join directions p on names.postDirection_id=p.id
left join suffixes x on names.suffix_id=x.id;
