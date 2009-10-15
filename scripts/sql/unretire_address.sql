/*
unretire_address.sql  L.Haley  03 October 2005

Usage: unretire_address.sql ARGUMENT LIST>
Examples: retire_address.sql <ARGUMENT LIST>

unretire_address.sql updates eng.mast_address_status, setting the status code equal to 1 (CURRENT),
, updates eng.mast_address_location_status setting the status code equal to 1 (CURRENT),and inserts a
record into eng.mast_address_assignment_hist detailing the action.

The script attemps to change the active status of the address to 'Y'.  This may produce an trigger error. Conintue the
script and check the address location record and make changes if necessary.


The user must commit the changes after the script is run.

ARGUMENT LIST

VARIABLE					TABLE.FIELD											TYPE		SAMPLE DATA
RetiredStreetAddressID	   		eng.mast_address.street_address_id	   					VARCHAR2,	100
RetiredLocationID               eng.mast_address_assignment_hist.location_id
&ContactID                  eng.mast_address_assignment_hist.contact_id
&REASON_FOR_NOTES           eng.mast_address_assignment_hist.notes


If used from golden, the arguments will be prompted.  If called from sqlplus, any null value
must be passed as NULL.

*/

select * from eng.mast_addr_assignment_contact;

--UnRetire Address Status
UPDATE eng.mast_address_status
	SET status_code = '1', end_date = NULL
	WHERE street_address_id = '&RetiredStreetAddressID';

--Add record for assignment history for address/location as UNRETIRED
INSERT INTO eng.mast_address_assignment_hist
	(location_id, action_date, action, contact_id, notes, street_address_id)
	VALUES ('&RetiredLocationID', SYSDATE, 'UNRETIRED', '&ContactID', '&REASON_FOR_NOTES',
	'&RetiredStreetAddressID');

--UnRetire the location status
UPDATE eng.mast_address_location_status
	SET status_code = 1, effective_end_date = NULL
	WHERE location_id = '&RetiredLocationID';

-- Delete and recreate address_location records
delete from eng.address_location
	where location_id = '&RetiredLocationID'
	and street_address_id in ( '&RetiredStreetAddressID') ;

-- Recreate the address location records with proper active field
insert into eng.address_location
	values ('&RetiredLocationID', '&ACTIVE_LOCATIONTYPE', '&RetiredStreetAddressID', NULL, '&ACTIVE_MAILABLE', '&ACTIVE_LIVABLE', NULL, 'Y' ) ;


--UPDATE eng.address_location
  --	SET active = 'Y'
	--WHERE location_id = '&RetiredLocationID'
	--AND street_address_id = '&RetiredStreetAddressID';


-- query the final state of the assignment

select distinct street_number "NUMBER", msn.street_direction_code || ' ' || msn.street_name || ' ' || msn.street_type_suffix_code || ' ' ||
msn.post_direction_suffix_code "STREET" ,ma.street_address_id "ADDRESS_ID",  mas.status_code  "ADDRESS STATUS",
mas.start_date "ADDRESS START DATE", mas.end_date "ADDRESS END DATE" ,al.location_id, ls.status_code "LOCATION STATUS",
ls.effective_start_date "LOCATION START DATE", ls.effective_end_date "LOCATION END DATE"
	from eng.mast_address ma, eng.mast_address_status mas, eng.address_location al, eng.mast_address_location_status ls,
	eng.mast_street_names msn
	where al.location_id = '&RetiredLocationID'
	and al.street_address_id = '&RetiredStreetAddressID'
	and ma.street_address_id = al.street_address_id
	and ma.street_address_id = mas.street_address_id
	and al.location_id = ls.location_id
	and ma.street_id = msn.street_id
	and msn.street_name_type = 'STREET'
	order by street_number, ma.street_address_id;
