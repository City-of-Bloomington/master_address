create or replace PROCEDURE     move_corner_address (
	vGoodStreetNumber		IN	VARCHAR2,
	cGoodStreet_direction	IN	CHAR,
	vGoodStreetName			IN	VARCHAR2,
	vGoodStreetSuffix		IN	VARCHAR2,
	cGoodPost_direction		IN	CHAR,
	vGoodTown				IN	VARCHAR2,
	vInactiveStreetNumber		IN	VARCHAR2,
	cInactiveStreet_direction	IN	CHAR,
	vInactiveStreetName			IN	VARCHAR2,
	vInactiveStreetSuffix		IN	VARCHAR2,
	cInactivePost_direction		IN	CHAR,
	vInactiveTown				IN	VARCHAR2,
	nInactiveAddressStatusCode  IN NUMBER,
	--vGoodNotes					IN	VARCHAR2,
	--vInactiveNotes					IN	VARCHAR2,
   --	vInactiveLocationTypeID			IN	VARCHAR2,
   --	nInactiveMailable				IN	NUMBER,
   --	nInactiveLivable				IN	NUMBER,
	nContactID				IN	NUMBER,
	vMessage				OUT	VARCHAR2)
	 IS

	myGoodStreetID				NUMBER;
	myInactiveStreetID				NUMBER;
	myGoodStreetAddressID		NUMBER;
	myInactiveStreetAddressID		NUMBER;
	myGoodTownID				NUMBER;
	myInactiveTownID				NUMBER;
	myGoodLocationID			NUMBER;
	myInactiveLocationID			NUMBER;
	myLocationTypeID		NUMBER;
	myInactiveBuildingID	NUMBER;
	good_street_not_found 	EXCEPTION;
	inactive_street_not_found 	EXCEPTION;
	good_address_not_found	EXCEPTION;
	inactive_address_not_found	EXCEPTION;
	good_location_not_found	EXCEPTION;
	inactive_location_not_found	EXCEPTION;
	myNum					NUMBER;
	errorblock				CHAR(1);

	BEGIN

	    errorblock:='A';
    		-- Find the town for good corner address
 		IF vGoodTown IS NOT NULL THEN
			SELECT town_id INTO myGoodTownID FROM eng.towns_master WHERE UPPER(description) = UPPER(vGoodTown);
	    ELSE
	    	myGoodTownID := NULL;
		END IF;

	    errorblock:='B';
    		-- Find the town for inactive corner address

 		IF vInactiveTown IS NOT NULL THEN
			SELECT town_id INTO myInactiveTownID FROM eng.towns_master WHERE UPPER(description) = UPPER(vInactiveTown);
	    ELSE
	    	myInactiveTownID := NULL;
		END IF;


		    -- Find the street for good address
		errorblock:='C';
		SELECT COUNT(s.street_id) INTO myNum FROM eng.mast_street s, eng.mast_street_names n
			WHERE s.street_id = n.street_id
			  AND UPPER(n.street_name) = UPPER(vGoodStreetName)
			  AND (s.town_id = myGoodTownID OR s.town_id IS NULL)
			  AND n.street_direction_code = cGoodStreet_direction
			  AND NVL(n.post_direction_suffix_code,'XXX') = NVL(cGoodPost_direction,'XXX')
			  AND (s.status_code = 1 OR s.status_code = 3)
			  AND NVL(n.street_type_suffix_code,'XXX') = NVL(vGoodStreetSuffix,'XXX')
			  AND n.street_name_type = 'STREET';
		IF myNum = 0 THEN RAISE good_street_not_found;
		END IF;

		-- Generate the street ID  for good address
		errorblock:='D';
		SELECT s.street_id INTO myGoodStreetID FROM eng.mast_street s, eng.mast_street_names n
			WHERE s.street_id = n.street_id
			  AND UPPER(n.street_name) = UPPER(vGoodStreetName)
			  AND (s.town_id = myGoodTownID OR s.town_id IS NULL)
			  AND n.street_direction_code = cGoodStreet_direction
			  AND NVL(n.post_direction_suffix_code,'XXX') = NVL(cGoodPost_direction,'XXX')
			  AND (s.status_code = 1 OR s.status_code = 2)
			  AND NVL(n.street_type_suffix_code,'XXX') = NVL(vGoodStreetSuffix,'XXX')
			  --AND n.street_name_type = 'STREET';
			  AND n.street_name_type != 'ALIAS';


		    -- Find the street for inactive corner address
		errorblock:='E';
		SELECT COUNT(s.street_id) INTO myNum FROM eng.mast_street s, eng.mast_street_names n
			WHERE s.street_id = n.street_id
			  AND UPPER(n.street_name) = UPPER(vInactiveStreetName)
			  AND (s.town_id = myInactiveTownID OR s.town_id IS NULL)
			  AND n.street_direction_code = cInactiveStreet_direction
			  AND NVL(n.post_direction_suffix_code,'XXX') = NVL(cInactivePost_direction,'XXX')
			  AND s.status_code = 1
			  AND NVL(n.street_type_suffix_code,'XXX') = NVL(vInactiveStreetSuffix,'XXX')
			  AND n.street_name_type = 'STREET';
		IF myNum = 0 THEN RAISE inactive_street_not_found;
		END IF;

		-- Generate the street ID  for inactive corner address
		errorblock:='F';
		SELECT s.street_id INTO myInactiveStreetID FROM eng.mast_street s, eng.mast_street_names n
			WHERE s.street_id = n.street_id
			  AND UPPER(n.street_name) = UPPER(vInactiveStreetName)
			  AND (s.town_id = myInactiveTownID OR s.town_id IS NULL)
			  AND n.street_direction_code = cInactiveStreet_direction
			  AND NVL(n.post_direction_suffix_code,'XXX') = NVL(cInactivePost_direction,'XXX')
			  AND s.status_code = 1
			  AND NVL(n.street_type_suffix_code,'XXX') = NVL(vInactiveStreetSuffix,'XXX')
			  AND n.street_name_type = 'STREET';


		-- Check to see if the good address  exists
		-- only checking STREET address types
		errorblock:='G';
		SELECT COUNT(*) INTO myNum FROM eng.mast_address  WHERE street_id = myGoodStreetID
		   AND street_number = vGoodStreetNumber
		   AND address_type = 'STREET';

		IF myNum = 0 THEN RAISE good_address_not_found;
		END IF;

		-- Get good address street_address_id
		errorblock:='H';
		SELECT street_address_id INTO myGoodStreetAddressID FROM eng.mast_address  WHERE street_id = myGoodStreetID
		   AND street_number = vGoodStreetNumber
		   AND address_type = 'STREET';

		-- Check to see if the inactive address  exists
		-- only checking STREET address types
		errorblock:='I';
		SELECT COUNT(*) INTO myNum FROM eng.mast_address  WHERE street_id = myInactiveStreetID
		   AND street_number = vInactiveStreetNumber
		   AND address_type = 'STREET';

		IF myNum = 0 THEN RAISE inactive_address_not_found;
		END IF;

		-- Get inactiveaddress street_address_id
		errorblock:='J';
		SELECT street_address_id INTO myInactiveStreetAddressID FROM eng.mast_address  WHERE street_id = myInactiveStreetID
		   AND street_number = vInactiveStreetNumber
		   AND address_type = 'STREET';


		-- Check to see if location exists for good address
		errorblock:='K';
		SELECT COUNT(*) INTO myNum FROM eng.address_location al, eng.mast_address ma, eng.mast_address_status mas
		   WHERE al.street_address_id = myGoodStreetAddressID
		   AND al.street_address_id = ma.street_address_id
		   AND ma.street_address_id = mas.street_address_id
		   AND mas.status_code = 1
		   AND al.subunit_id is NULL;

		IF myNum = 0 THEN RAISE good_location_not_found;
		END IF;

		-- Get the location id for good address
		errorblock:='L';
		SELECT al.location_id INTO myGoodLocationID
		FROM eng.address_location al, eng.mast_address ma, eng.mast_address_status mas
		   WHERE al.street_address_id = myGoodStreetAddressID
		   AND al.street_address_id = ma.street_address_id
		   AND ma.street_address_id = mas.street_address_id
		   AND mas.status_code = 1
		   AND al.subunit_id is NULL;


		-- Check to see if location exists for inactive address
		errorblock:='M';
		SELECT COUNT(*) INTO myNum FROM eng.address_location al, eng.mast_address ma, eng.mast_address_status mas
		   WHERE al.street_address_id = myInactiveStreetAddressID
		   AND al.street_address_id = ma.street_address_id
		   AND ma.street_address_id = mas.street_address_id
		   AND mas.status_code = 1
		   AND al.subunit_id is NULL;

		IF myNum = 0 THEN RAISE inactive_location_not_found;
		END IF;

		-- Get the location id for inactive address
		errorblock:='N';
		SELECT al.location_id INTO myInactiveLocationID
		FROM eng.address_location al, eng.mast_address ma, eng.mast_address_status mas
		   WHERE al.street_address_id = myInactiveStreetAddressID
		   AND al.street_address_id = ma.street_address_id
		   AND ma.street_address_id = mas.street_address_id
		   AND mas.status_code = 1
		   AND al.subunit_id is NULL;

		-- Deal with building tables for inactive locations if they exist
		errorblock:='M';
		SELECT COUNT(*)INTO myNum FROM gis.building_address_location
		   WHERE location_id = myInactiveLocationID;

		IF myNum > 0 THEN
			SELECT building_id INTO myInactiveBuildingID FROM gis.building_address_location
				WHERE location_id = myInactiveLocationID;
		   --	DELETE FROM gis.building_address_location
				--WHERE location_id = myInactiveLocationID;
			UPDATE gis.buildings SET status_code = 4, effective_end_date = SYSDATE
				WHERE building_id = myInactiveBuildingID;

		END IF;


		-- Update Location Assignment History for good location
	   errorblock:='T';
	   INSERT INTO eng.mast_address_assignment_hist (location_id, action_date, action, contact_id, notes, street_address_id)
	   	VALUES (myGoodLocationID, SYSDATE, 'MOVED TO LOCATION', nContactID, 'CORNER LOT', myInactiveStreetAddressID);

		-- Update Location Assignment History for inactive location
		errorblock:='O';
		INSERT INTO eng.mast_address_assignment_hist (location_id, action_date, action, contact_id, notes, street_address_id)
		 VALUES (myInactiveLocationID, SYSDATE, 'RETIRED LOCATION', nContactID, 'DUPLICATE CORNER LOT LOCATION', myInactiveStreetAddressID);

		-- Move inactive address to good location
		errorblock:='P';
		INSERT into eng.address_location (location_id, location_type_id, street_address_id, subunit_id, mailable_flag,
		livable_flag, common_name, active)
			VALUES (myGoodLocationID, 'UNKNOWN', myInactiveStreetAddressID, NULL, 0, 0, NULL, 'N') ;

		-- Set location of  inactive address inactive
		errorblock:='Q';
		UPDATE eng.address_location SET  active = 'N'
			WHERE location_id = myInactiveLocationID;

		-- Append notes for good address
		errorblock := 'U';
		--UPDATE eng.mast_address set notes = notes || ' ' || vGoodNotes WHERE street_address_id = myGoodStreetAddressID ;
		UPDATE eng.mast_address set notes = notes || ' CORNER LOT WITH ' || vInactiveStreetNumber||' '||cInactiveStreet_direction||' '||vInactiveStreetName||' '||vInactiveStreetSuffix||' '||cInactivePost_direction
			WHERE street_address_id = myGoodStreetAddressID ;

		-- Append notes for inactive address
		errorblock := 'V';
		--UPDATE eng.mast_address set notes = notes || ' ' || vInactiveNotes WHERE street_address_id = myInactiveStreetAddressID ;
		UPDATE eng.mast_address set notes = notes || ' CORNER LOT WITH ' || vGoodStreetNumber||' '||cGoodStreet_direction||' '||vGoodStreetName||' '||vGoodStreetSuffix||' '||cGoodPost_direction
			WHERE street_address_id = myInactiveStreetAddressID ;


   		-- Update address status for Inactive address.
		errorblock:='R';
		UPDATE eng.mast_address_status SET status_code = nInactiveAddressStatusCode
			WHERE street_address_id = myInactiveStreetAddressID;


       -- If address status retired, set end date
		errorblock:='S';
		SELECT COUNT(*) INTO myNum FROM eng.mast_address_status  WHERE street_address_id = myInactiveStreetAddressID
		   AND status_code = 3;

		IF myNum = 1 THEN
			UPDATE eng.mast_address_status SET end_date = SYSDATE
				WHERE street_address_id = myInactiveStreetAddressID;
	   		INSERT INTO eng.mast_address_assignment_hist (location_id, action_date, action, contact_id, notes, street_address_id)
	   			VALUES (myGoodLocationID, SYSDATE, 'RETIRED', nContactID, 'UNUSED CORNER LOT', myInactiveStreetAddressID);
	   	   	UPDATE eng.mast_address set notes = notes || ' - NOT USED'
		   		WHERE street_address_id = myInactiveStreetAddressID ;

		END IF;

		-- Retire the Inactive location
		errorblock := 'T';
		UPDATE eng.mast_address_location_status SET status_code = 3, effective_end_date = SYSDATE
			WHERE location_id = myInactiveLocationID ;


		-- Generate the status message

		vMessage:='Corner lot '||vInactiveStreetNumber||' '||cInactiveStreet_direction||' '||vInactiveStreetName||' '||vInactiveStreetSuffix||' '||cInactivePost_direction||
		' Address ID '|| myInactiveStreetAddressID|| ', Location ID ' ||myInactiveLocationID||' successfully moved to Location ID '||myGoodLocationID;

	COMMIT;

	EXCEPTION
		WHEN good_address_not_found THEN
			vMessage:=vGoodStreetNumber||' '||cGoodStreet_direction||' '||vGoodStreetName||' '||vGoodStreetSuffix||' '||cGoodPost_direction||' good address not found';
		WHEN inactive_address_not_found THEN
			vMessage:=vInactiveStreetNumber||' '||cInactiveStreet_direction||' '||vInactiveStreetName||' '||vInactiveStreetSuffix||' '||cInactivePost_direction||' inactive address not found';
		WHEN good_street_not_found THEN
			vMessage:=cGoodStreet_direction||' '||vGoodStreetName||' '||vGoodStreetSuffix||' '||cGoodPost_direction||' good street not found';
		WHEN inactive_street_not_found THEN
			vMessage:=cInactiveStreet_direction||' '||vInactiveStreetName||' '||vInactiveStreetSuffix||' '||cInactivePost_direction||' inactive street not found';
		WHEN good_location_not_found THEN
			vMessage:=vGoodStreetNumber||' '||cGoodStreet_direction||' '||vGoodStreetName||' '||vGoodStreetSuffix||' '||cGoodPost_direction||' good location not found';
		WHEN inactive_location_not_found THEN
			vMessage:=vInactiveStreetNumber||' '||cInactiveStreet_direction||' '||vInactiveStreetName||' '||vInactiveStreetSuffix||' '||cInactivePost_direction||' inactive location not found';
		WHEN no_data_found THEN
			vMessage:='Some other error has occurred in block '||errorblock;
	END;
