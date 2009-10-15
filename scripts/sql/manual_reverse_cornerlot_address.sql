--manual_revers_cornerlot_address.sql 03 October 2003 L.Haley

-- This script reverses active and inactive addresses for corner lots attached to a single location.


-- Query the location
select * from eng.address_location where location_id = '&LocationID' ;

-- Set address status to CURRENT for active corner lot
update eng.mast_address_status
	set status_code = '1', end_date = NULL
	where street_address_id = '&ActiveStreetAddressID' ;

-- Set address status to RETIRED for inactive corner lot
update eng.mast_address_status
	set status_code = '3', end_date = SYSDATE
	where street_address_id = '&InactiveStreetAddressID'  ;

--There is a trigger issue with just updating the active field for address locations, so to be sure it gets done without error,
--	we need to delete the records and recreate them.

-- Delete and recreate address_location records
delete from eng.address_location
	where location_id = '&LocationID'
	and street_address_id in ( '&ActiveStreetAddressID', '&InActiveStreetAddressID') ;

-- Recreate the address location records with proper active field
insert into eng.address_location
	values ('&LocationID', '&ACTIVE_LOCATIONTYPE', '&ActiveStreetAddressID', NULL, '&ACTIVE_MAILABLE', '&ACTIVE_LIVABLE', NULL, 'Y' ) ;

insert into eng.address_location
	values ('&LocationID', '&INACTIVE_LOCATIONTYPE', '&InActiveStreetAddressID', NULL, 0, 0, NULL, 'N' ) ;

-- Insert records into Assignment History
insert into eng.mast_address_assignment_hist
	values ('&LocationID', SYSDATE, 'UNRETIRED','&CONTACTID', 'UNRETIRED RETIRED CORNER LOT', '&ActiveStreetAddressID', NULL) ;

insert into eng.mast_address_assignment_hist
	values ('&LocationID', SYSDATE, 'RETIRED','&CONTACTID', 'RETIRED CORNER LOT', '&InActiveStreetAddressID', NULL) ;

-- Re-Query the location to see the result.
select * from eng.address_location where location_id = '&LocationID' ;



