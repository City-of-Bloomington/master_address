create or replace PROCEDURE reassign_address (
	vStreetNumber		IN	VARCHAR2,
	cStreet_direction	IN	CHAR,
	vStreetName			IN	VARCHAR2,
	vStreetSuffix		IN	VARCHAR2,
	cPost_direction		IN	CHAR,
	vTown				IN	VARCHAR2,
	--vAddressType			IN	VARCHAR2,
	--vStreetAddress2			IN	VARCHAR2,
	--vCity					IN	VARCHAR2,
	--vzip					IN	VARCHAR2,
	--vZipPlus4				IN	VARCHAR2,
	--vAddressJurisdiction	IN	VARCHAR2,
	--vGovtJurisdiction		IN	VARCHAR2,
	--vTaxJurisdiction		IN	CHAR,
	--vQuarterSection			IN	VARCHAR2,
	--vTownship				IN	VARCHAR2,
	--vSection				IN	VARCHAR2,
   --SubdivisionID			IN	NUMBER,
	--nPlatID					IN	NUMBER,
	--vPlatLotNumber			IN	VARCHAR2,
	--vCensusBlock			IN	VARCHAR2,
	--nX						IN	NUMBER,
	--nY						IN	NUMBER,
	--nLat					IN	NUMBER,
	--nLong					IN	NUMBER,
	--vNotes					IN	VARCHAR2,
	vLocationType			IN	VARCHAR2,
	nMailable				IN	NUMBER,
	nLivable				IN	NUMBER,
	nContactID				IN	NUMBER,
	vAssignmentNotes		IN	VARCHAR2,
	vMessage				OUT	VARCHAR2)
	 IS

	myStreetID				NUMBER;
	myStreetAddressID		NUMBER;
	myTownID				NUMBER;
	myTownshipID			NUMBER;
	--myAddressJurisdictionID NUMBER;
 	--myGovtJurisdictionID	NUMBER;
	myLocationID			NUMBER;
	myLocationTypeID		NUMBER;
	address_not_found	EXCEPTION;
	street_not_found 		EXCEPTION;
	myNum					NUMBER;
	errorblock				CHAR(1);

	BEGIN

	    errorblock:='A';
    		-- Find the town
 		IF vTown IS NOT NULL THEN
			SELECT town_id INTO myTownID FROM eng.towns_master WHERE UPPER(description) = UPPER(vTown);
	    ELSE
	    	myTownID := NULL;
		END IF;

		errorblock:='B';
		    -- Find the street
		SELECT COUNT(s.street_id) INTO myNum FROM eng.mast_street s, eng.mast_street_names n
			WHERE s.street_id = n.street_id
			  AND UPPER(n.street_name) = UPPER(vStreetName)
			  AND (s.town_id = myTownID OR s.town_id IS NULL)
			  AND n.street_direction_code = cStreet_direction
			  AND NVL(n.post_direction_suffix_code,'XXX') = NVL(cPost_direction,'XXX')
			  AND s.status_code = 1
			  AND NVL(n.street_type_suffix_code,'XXX') = NVL(vStreetSuffix,'XXX')
			  AND n.street_name_type = 'STREET';
		errorblock:='C';
		IF myNum = 0 THEN RAISE street_not_found;
		END IF;

		-- Check to see if the address already exists

  /*
  		-- Find the township
  		IF vTownship IS NOT NULL THEN
  			SELECT township_id INTO myTownshipID FROM eng.township_master WHERE UPPER(name) =  UPPER(vTownship);
  		ELSE
  			myTownshipID:=NULL;
  		END IF;

  		-- Find the Address Jurisdiction
		errorblock:='D';

		IF vAddressJurisdiction IS NOT NULL THEN
  			SELECT jurisdiction_id INTO myAddressJurisdictionID FROM eng.addr_jurisdiction_master WHERE
  			UPPER(description) = UPPER(vAddressJurisdiction);
  		ELSE
  			myAddressJurisdictionID :=NULL;
  		END IF;

  		-- Find the Government Jurisdiction
		errorblock:='E';
		IF vGovtJurisdiction IS NOT NULL THEN
    		SELECT gov_jur_id INTO myGovtJurisdictionID FROM eng.governmental_jurisdiction_mast WHERE
			UPPER(description) = UPPER(vGovtJurisdiction);
		ELSE
			myGovtJurisdictionID:=NULL;
		END IF;
	*/

		-- Check to see if the address  exists
		-- only checking STREET address types
		errorblock:='J';
		SELECT COUNT(*) INTO myNum FROM eng.mast_address  WHERE street_id = myStreetID
		   AND street_number = vStreetNumber
		   AND address_type = 'STREET';

		IF myNum = 0 THEN RAISE address_not_found;
		END IF;

		-- Get old address street_address_id
		errorblock:='K';
		SELECT street_address_id INTO myStreetAddressID FROM eng.mast_address  WHERE street_id = myStreetID
		   AND street_number = vStreetNumber
		   AND address_type = 'STREET';


		-- Create the address  - Using existing address, no need
		/*
		errorblock:='I';
		INSERT INTO ENG.MAST_ADDRESS (STREET_ADDRESS_ID, STREET_NUMBER, STREET_ID, ADDRESS_TYPE,
			TAX_JURISDICTION, JURISDICTION_ID, GOV_JUR_ID, TOWNSHIP_ID, SECTION,
			QUARTER_SECTION, SUBDIVISION_ID, PLAT_ID, PLAT_LOT_NUMBER,
			STREET_ADDRESS_2, CITY, STATE, ZIP, ZIPPLUS4, CENSUS_BLOCK_FIPS_CODE,
			STATE_PLANE_X_COORDINATE, STATE_PLANE_Y_COORDINATE, LATITUDE, LONGITUDE,
			NOTES)
		VALUES (myStreetAddressID, vStreetNumber, myStreetID, vAddressType,
			vTaxJurisdiction, myAddressJurisdictionID, myGovtJurisdictionID, myTownshipID, vSection,
			vQuarterSection, nSubdivisionID, nPlatID, vPlatLotNumber,
			vStreetAddress2, vCity, 'IN', vZip, vZipPlus4, vCensusBlock, nX, nY, nLat, nLong, vNotes);
		*/

		-- Need a new location Location
		errorblock:='J';
		SELECT eng.location_id_s.nextval INTO myLocationID FROM DUAL;

		INSERT INTO eng.address_location (location_id, location_type_id, street_address_id, mailable_flag,livable_flag, active)
		 VALUES (myLocationID, vLocationType ,myStreetAddressID, nMailable, nLivable, 'Y');

		-- Assignment History
		-- Note that we defaulted the contact to NULL

		errorblock:='K';
		INSERT INTO eng.mast_address_assignment_hist (location_id, action_date, action, contact_id, notes, street_address_id)
		 VALUES (myLocationID, SYSDATE, 'RE-ASSIGN', nContactID, vAssignmentNotes, myStreetAddressID);

		-- Create the status for the location
		errorblock := 'L';
		INSERT INTO eng.mast_address_location_status (status_code, effective_start_date, location_id)
		 VALUES (1, SYSDATE, myLocationID);

		-- Update the status for the address
		errorblock := 'M';
		UPDATE eng.mast_address_status SET status_code = 1, end_date = NULL, start_date = SYSDATE
			WHERE street_address_id = myStreetAddressID;


		-- Generate the status message

		vMessage:=vStreetNumber||' '||cStreet_direction||' '||vStreetName||' '||vStreetSuffix||' '||cPost_direction||' StreetAddressID'|| ' ' ||myStreetAddressID|| ' successfully reassigned as Location ID '||myLocationID;

	COMMIT;

	EXCEPTION
		WHEN address_not_found THEN
			vMessage:=vStreetNumber||' '||cStreet_direction||' '||vStreetName||' '||vStreetSuffix||' '||cPost_direction||' not found';
		WHEN street_not_found THEN
			vMessage:=cStreet_direction||' '||vStreetName||' '||vStreetSuffix||' '||cPost_direction||' not found';
		WHEN no_data_found THEN
			vMessage:='Some other error has occurred in block '||errorblock;
	END;
