create or replace PROCEDURE                      change_address (
	vOldStreetNumber		IN	VARCHAR2,
	cOldStreet_direction	IN	CHAR,
	vOldStreetName			IN	VARCHAR2,
	vOldStreetSuffix		IN	VARCHAR2,
	cOldPost_direction		IN	CHAR,
	vOldTown				IN	VARCHAR2,
	vOldStreetAddress2			IN	VARCHAR2,
	vOldAssignmentNotes		IN	VARCHAR2,
	vNewStreetNumber		IN	VARCHAR2,
	cNewStreet_direction	IN	CHAR,
	vNewStreetName			IN	VARCHAR2,
	vNewStreetSuffix		IN	VARCHAR2,
	cNewPost_direction		IN	CHAR,
	vNewTown				IN	VARCHAR2,
	vAddressType			IN	VARCHAR2,
	vNewStreetAddress2			IN	VARCHAR2,
	vCity					IN	VARCHAR2,
	vzip					IN	VARCHAR2,
	vZipPlus4				IN	VARCHAR2,
	vAddressJurisdiction	IN	VARCHAR2,
	vGovtJurisdiction		IN	VARCHAR2,
	vTaxJurisdiction		IN	CHAR,
	vQuarterSection			IN	VARCHAR2,
	vTownship				IN	VARCHAR2,
	vSection				IN	VARCHAR2,
	nSubdivisionID			IN	NUMBER,
	nPlatID					IN	NUMBER,
	vPlatLotNumber			IN	VARCHAR2,
	vCensusBlock			IN	VARCHAR2,
	nX						IN	NUMBER,
	nY						IN	NUMBER,
	nLat					IN	NUMBER,
	nLong					IN	NUMBER,
	vNotes					IN	VARCHAR2,
	vLocationType			IN	VARCHAR2,
	nMailable				IN	NUMBER,
	nLivable				IN	NUMBER,
	nContactID				IN	NUMBER,
	vNewAssignmentNotes		IN	VARCHAR2,
	vMessage				OUT	VARCHAR2)
	 IS

	myOldStreetID				NUMBER;
	myNewStreetID				NUMBER;
	myOldStreetAddressID		NUMBER;
	myNewStreetAddressID		NUMBER;
	myOldTownID				NUMBER;
	myNewTownID				NUMBER;
	myTownshipID			NUMBER;
	myAddressJurisdictionID NUMBER;
	myGovtJurisdictionID	NUMBER;
	myOldLocationID			NUMBER;
	myNewLocationID			NUMBER;
	myLocationTypeID		NUMBER;
	myX						NUMBER;
	myY						NUMBER;
	old_address_not_found	EXCEPTION;
	address_already_exists	EXCEPTION;
	old_street_not_found 	EXCEPTION;
	new_street_not_found 	EXCEPTION;
	old_location_not_found	EXCEPTION;
	myNum					NUMBER;
	errorblock				CHAR(1);

	BEGIN

	    errorblock:='A';
    		-- Find the old town
 		IF vOldTown IS NOT NULL THEN
			SELECT town_id INTO myOldTownID FROM eng.towns_master WHERE UPPER(description) = UPPER(vOldTown);
	    ELSE
	    	myOldTownID := NULL;
		END IF;

	    errorblock:='B';
    		-- Find the new town

 		IF vNewTown IS NOT NULL THEN
			SELECT town_id INTO myNewTownID FROM eng.towns_master WHERE UPPER(description) = UPPER(vNewTown);
	    ELSE
	    	myNewTownID := NULL;
		END IF;


		    -- Find the old street
		errorblock:='C';
		SELECT COUNT(s.street_id) INTO myNum FROM eng.mast_street s, eng.mast_street_names n
			WHERE s.street_id = n.street_id
			  AND UPPER(n.street_name) = UPPER(vOldStreetName)
			  AND (s.town_id = myOldTownID OR s.town_id IS NULL)
			  AND n.street_direction_code = cOldStreet_direction
			  AND NVL(n.post_direction_suffix_code,'XXX') = NVL(cOldPost_direction,'XXX')
			  AND (s.status_code = 1 OR s.status_code = 3)
			  AND NVL(n.street_type_suffix_code,'XXX') = NVL(vOldStreetSuffix,'XXX')
			  AND n.street_name_type = 'STREET';
		IF myNum = 0 THEN RAISE old_street_not_found;
		END IF;

		-- Generate the old street ID
		errorblock:='D';
		SELECT s.street_id INTO myOldStreetID FROM eng.mast_street s, eng.mast_street_names n
			WHERE s.street_id = n.street_id
			  AND UPPER(n.street_name) = UPPER(vOldStreetName)
			  AND (s.town_id = myOldTownID OR s.town_id IS NULL)
			  AND n.street_direction_code = cOldStreet_direction
			  AND NVL(n.post_direction_suffix_code,'XXX') = NVL(cOldPost_direction,'XXX')
			  AND (s.status_code = 1 OR s.status_code = 2)
			  AND NVL(n.street_type_suffix_code,'XXX') = NVL(vOldStreetSuffix,'XXX')
			  --AND n.street_name_type = 'STREET';
			  AND n.street_name_type != 'ALIAS';


		    -- Find the new street
		errorblock:='E';
		SELECT COUNT(s.street_id) INTO myNum FROM eng.mast_street s, eng.mast_street_names n
			WHERE s.street_id = n.street_id
			  AND UPPER(n.street_name) = UPPER(vNewStreetName)
			  AND (s.town_id = myNewTownID OR s.town_id IS NULL)
			  AND n.street_direction_code = cNewStreet_direction
			  AND NVL(n.post_direction_suffix_code,'XXX') = NVL(cNewPost_direction,'XXX')
			  AND s.status_code = 1
			  AND NVL(n.street_type_suffix_code,'XXX') = NVL(vNewStreetSuffix,'XXX')
			  AND n.street_name_type = 'STREET';
		IF myNum = 0 THEN RAISE new_street_not_found;
		END IF;

		-- Generate the new street ID
		errorblock:='F';
		SELECT s.street_id INTO myNewStreetID FROM eng.mast_street s, eng.mast_street_names n
			WHERE s.street_id = n.street_id
			  AND UPPER(n.street_name) = UPPER(vNewStreetName)
			  AND (s.town_id = myNewTownID OR s.town_id IS NULL)
			  AND n.street_direction_code = cNewStreet_direction
			  AND NVL(n.post_direction_suffix_code,'XXX') = NVL(cNewPost_direction,'XXX')
			  AND s.status_code = 1
			  AND NVL(n.street_type_suffix_code,'XXX') = NVL(vNewStreetSuffix,'XXX')
			  AND n.street_name_type = 'STREET';


  		-- Find the township
		errorblock:='G';
  		IF vTownship IS NOT NULL THEN
  			SELECT township_id INTO myTownshipID FROM eng.township_master WHERE UPPER(name) =  UPPER(vTownship);
  		ELSE
  			myTownshipID:=NULL;
  		END IF;

  		-- Find the Address Jurisdiction
		errorblock:='H';

		IF vAddressJurisdiction IS NOT NULL THEN
  			SELECT jurisdiction_id INTO myAddressJurisdictionID FROM eng.addr_jurisdiction_master WHERE
  			UPPER(description) = UPPER(vAddressJurisdiction);
  		ELSE
  			myAddressJurisdictionID :=NULL;
  		END IF;

  		-- Find the Government Jurisdiction
		errorblock:='I';
		IF vGovtJurisdiction IS NOT NULL THEN
    		SELECT gov_jur_id INTO myGovtJurisdictionID FROM eng.governmental_jurisdiction_mast WHERE
			UPPER(description) = UPPER(vGovtJurisdiction);
		ELSE
			myGovtJurisdictionID:=NULL;
		END IF;

		-- Check to see if the old address  exists
		-- only checking STREET address types
		errorblock:='J';
		SELECT COUNT(*) INTO myNum FROM eng.mast_address  WHERE street_id = myOldStreetID
		   AND street_number = vOldStreetNumber
		   AND address_type = 'STREET';

		IF myNum = 0 THEN RAISE old_address_not_found;
		END IF;

		-- Get old address street_address_id
		errorblock:='K';
		SELECT street_address_id INTO myOldStreetAddressID FROM eng.mast_address  WHERE street_id = myOldStreetID
		   AND street_number = vOldStreetNumber
		   AND address_type = 'STREET';

		-- Get remaining address attributes
		errorblock:='U';
		IF nX is NULL THEN
			SELECT state_plane_x_coordinate INTO myX FROM eng.mast_address  WHERE street_id = myOldStreetID
		   	AND street_number = vOldStreetNumber
		   	AND address_type = 'STREET';
		ELSE
			myX:=nX;
		END IF;

  		IF nY is NULL THEN
			SELECT state_plane_y_coordinate INTO myY FROM eng.mast_address  WHERE street_id = myOldStreetID
		   	AND street_number = vOldStreetNumber
		   	AND address_type = 'STREET';
		ELSE
			myY:=nY;
		END IF;

		-- Check to see if location exists for old address
		errorblock:='L';
		SELECT COUNT(*) INTO myNum FROM eng.address_location al, eng.mast_address ma, eng.mast_address_status mas
		   WHERE al.street_address_id = myOldStreetAddressID
		   AND al.street_address_id = ma.street_address_id
		   AND ma.street_address_id = mas.street_address_id
		   AND mas.status_code = 1
		   AND al.subunit_id is NULL;

		IF myNum = 0 THEN RAISE old_location_not_found;
		END IF;

		-- Get the location id
		errorblock:='M';
		SELECT al.location_id INTO myOldLocationID
		FROM eng.address_location al, eng.mast_address ma, eng.mast_address_status mas
		   WHERE al.street_address_id = myOldStreetAddressID
		   AND al.street_address_id = ma.street_address_id
		   AND ma.street_address_id = mas.street_address_id
		   AND mas.status_code = 1
		   AND al.subunit_id is NULL;



		-- Check to see if the new address already exists
		-- in future, address_type may be part of key so we can have duplicate numbers
		errorblock:='N';
		SELECT COUNT(*)
		  INTO myNum
		  FROM eng.mast_address
		 WHERE street_id = myNewStreetID
		   AND street_number = vNewStreetNumber;

		IF myNum > 0 THEN RAISE address_already_exists;
		END IF;

		-- Find a new street address ID
		errorblock:='O';
		SELECT eng.street_address_id_s.NEXTVAL INTO myNewStreetAddressID FROM DUAL;



		-- Create the address
		errorblock:='P';
		INSERT INTO ENG.MAST_ADDRESS (STREET_ADDRESS_ID, STREET_NUMBER, STREET_ID, ADDRESS_TYPE,
			TAX_JURISDICTION, JURISDICTION_ID, GOV_JUR_ID, TOWNSHIP_ID, SECTION,
			QUARTER_SECTION, SUBDIVISION_ID, PLAT_ID, PLAT_LOT_NUMBER,
			STREET_ADDRESS_2, CITY, STATE, ZIP, ZIPPLUS4, CENSUS_BLOCK_FIPS_CODE,
			STATE_PLANE_X_COORDINATE, STATE_PLANE_Y_COORDINATE, LATITUDE, LONGITUDE,
			NOTES)
		VALUES (myNewStreetAddressID, vNewStreetNumber, myNewStreetID, vAddressType,
			vTaxJurisdiction, myAddressJurisdictionID, myGovtJurisdictionID, myTownshipID, vSection,
			vQuarterSection, nSubdivisionID, nPlatID, vPlatLotNumber,
			vNewStreetAddress2, vCity, 'IN', vZip, vZipPlus4, vCensusBlock, myX, myY, nLat, nLong, vNotes);


		-- New Location not needed - just insert new record into the old, but first make old inactive
		UPDATE eng.address_location SET active = 'N' where location_id = myOldLocationID;

	   errorblock:='Q';
	   --SELECT eng.location_id_s.nextval INTO myLocationID FROM DUAL;

	   INSERT INTO eng.address_location (location_id, location_type_id, street_address_id,
	   		mailable_flag,livable_flag, active)
	   VALUES (myOldLocationID, vLocationType ,myNewStreetAddressID, nMailable, nLivable, 'Y');

		-- Create the status for the new address address
		errorblock := 'S';
		INSERT into eng.mast_address_status (status_code, start_date, street_address_id)
		 VALUES (1, SYSDATE, myNewStreetAddressID);

		-- Update the status for the old address
		errorblock := 'T';
		UPDATE eng.mast_address_status
		SET status_code = '3', end_date = SYSDATE
		WHERE street_address_id = myOldStreetAddressID;



		-- Update Location Assignment History
		-- Note that we defaulted the contact to NULL

		-- First the one readdressed
		errorblock:='R';
		INSERT INTO eng.mast_address_assignment_hist (location_id, action_date, action, contact_id, notes, street_address_id)
		 VALUES (myOldLocationID, SYSDATE, 'READDRESSED', nContactID, vOldAssignmentNotes, myOldStreetAddressID);
		-- now the new assignment
		INSERT INTO eng.mast_address_assignment_hist (location_id, action_date, action, contact_id, notes, street_address_id)
		 VALUES (myOldLocationID, SYSDATE, 'ASSIGN', nContactID, vNewAssignmentNotes, myNewStreetAddressID);


		-- Create the status for the location
		-- no longer needed
	   --	errorblock := 'L';
	   --	INSERT INTO eng.mast_address_location_status (status_code, effective_start_date, location_id)
		-- VALUES (1, SYSDATE, myLocationID);


		-- Generate the status message

		vMessage:=vNewStreetNumber||' '||cNewStreet_direction||' '||vNewStreetName||' '||vNewStreetSuffix||' '||cNewPost_direction||
		' successfully changed from '|| vOldStreetNumber|| ' ' ||cOldStreet_direction||' '||vOldStreetName||' '||vOldStreetSuffix||' '||cOldPost_direction||
		'- Location ID '||myOldLocationID|| ' oldX '||myX|| ' oldY ' ||myY;

	COMMIT;

	EXCEPTION
		WHEN old_address_not_found THEN
			vMessage:=vOldStreetNumber||' '||cOldStreet_direction||' '||vOldStreetName||' '||vOldStreetSuffix||' '||cOldPost_direction||' old address not found';
		WHEN old_location_not_found THEN
			vMessage:=vOldStreetNumber||' '||cOldStreet_direction||' '||vOldStreetName||' '||vOldStreetSuffix||' '||cOldPost_direction||' old location not found';
		WHEN address_already_exists THEN
			vMessage:=vNewStreetNumber||' '||cNewStreet_direction||' '||vNewStreetName||' '||vNewStreetSuffix||' '||cNewPost_direction||' new address already exists';
		WHEN old_street_not_found THEN
			vMessage:=cOldStreet_direction||' '||vOldStreetName||' '||vOldStreetSuffix||' '||cOldPost_direction||' old street not found';
		WHEN new_street_not_found THEN
			vMessage:=cNewStreet_direction||' '||vNewStreetName||' '||vNewStreetSuffix||' '||cNewPost_direction||' new street not found';
		WHEN no_data_found THEN
			vMessage:='Some other error has occurred in block '||errorblock;
	END;
