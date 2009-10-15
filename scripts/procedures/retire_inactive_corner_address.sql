create or replace PROCEDURE     retire_inactive_corner_address (
	nGoodStreetAddressID		IN	NUMBER,
	nInactiveStreetAddressID		IN	NUMBER,
	nLocationID				IN	NUMBER,
	nContactID				IN	NUMBER,
	vMessage				OUT	VARCHAR2)
	 IS

	good_address_not_found	EXCEPTION;
	inactive_address_not_found	EXCEPTION;
	location_not_found	EXCEPTION;
	myNum					NUMBER;
	errorblock				CHAR(1);

	BEGIN

	-- Check to see if the active address  exists
	errorblock:='A';
	SELECT COUNT(*) INTO myNum FROM eng.mast_address  WHERE street_address_id = nGoodStreetAddressID;
	IF myNum = 0 THEN RAISE good_address_not_found;
	END IF;


	-- Check to see if the inactive address  exists
	errorblock:='B';
	SELECT COUNT(*) INTO myNum FROM eng.mast_address  WHERE street_address_id = nInactiveStreetAddressID;

	IF myNum = 0 THEN RAISE inactive_address_not_found;
	END IF;

	-- Check to see if location exists for good address
	errorblock:='C';
	SELECT COUNT(*) INTO myNum FROM eng.address_location  WHERE location_id = nLocationID;

	IF myNum = 0 THEN RAISE location_not_found;
	END IF;

	--Set good corner lot address status code current
	errorblock:='D';
	UPDATE eng.mast_address_status set status_code = '1'
		WHERE street_address_id = '&GoodStreetAddressID';

	-- Append address notes for inactive corner lot to indicate it is not being used
	errorblock:='E';
	UPDATE eng.mast_address SET notes = notes || ' ' || 'NOT USED'
		WHERE street_address_id = '&InactiveStreetAddressID' ;

	-- Insert retired action record into assignment history for inactive address
	errorblock:='F';
		INSERT INTO eng.mast_address_assignment_hist
	VALUES ('&nLocationID', SYSDATE,  'RETIRED', '&nContactID', 'UNUSED CORNER LOT', '&InactiveStreeAddressID', NULL) ;

	-- Set inactive corner address lot status code as retired and insert end date
	errorblock:='G';
	UPDATE eng.mast_address_status SET status_code = '3', end_date = SYSDATE
	 	WHERE street_address_id = '&InactiveStreetAddressID';

	-- Set location record for inactive corner lot address as inactive
	errorblock:='H';
	UPDATE eng.address_location SET active = 'N'
		WHERE location_id = '&LocationID' AND street_address_id = '&InactiveStreetAddressID'
   		AND subunit_id is NULL;

	COMMIT;

	-- Set location record for good corner lot address as active
	errorblock:='I';
	SELECT count(location_id) INTO myNum FROM eng.address_location
		WHERE location_id = '&LocationID' AND street_address_id = '&GoodStreetAddressID' and active = 'Y';

		IF myNum = 0 THEN
		UPDATE eng.address_location set active = 'Y'
			WHERE location_id = '&LocationID' and street_address_id = '&GoodStreetAddressID' and subunit_id is NULL;
    	END IF;


	-- Generate the status message
	vMessage:='Inactive Corner lot '||nInactiveStreetAddressID||' successfully retired';

	COMMIT;


	EXCEPTION
		WHEN good_address_not_found THEN
			vMessage:=nGoodStreetAddressID||' good address not found';
		WHEN inactive_address_not_found THEN
			vMessage:=nInactiveStreetAddressID||' inactive address not found';
		WHEN location_not_found THEN
			vMessage:=nLocationID||' good location not found';
		WHEN no_data_found THEN
			vMessage:='Some other error has occurred in block '||errorblock;
END ;
