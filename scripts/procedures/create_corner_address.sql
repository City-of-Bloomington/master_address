create or replace PROCEDURE     create_corner_address (
	vStreetNumber1		IN	VARCHAR2,
	cStreet_direction1	IN	CHAR,
	vStreetName1			IN	VARCHAR2,
	vStreetSuffix1		IN	VARCHAR2,
	cPost_direction1		IN	CHAR,
	vTown1				IN	VARCHAR2,
	vStreetAddress2_1			IN	VARCHAR2,
	vStreetNumber2		IN	VARCHAR2,
	cStreet_direction2	IN	CHAR,
	vStreetName2			IN	VARCHAR2,
	vStreetSuffix2		IN	VARCHAR2,
	cPost_direction2		IN	CHAR,
	vTown2				IN	VARCHAR2,
	vStreetAddress2_2			IN	VARCHAR2,
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
	vNotes1					IN	VARCHAR2,
	vNotes2					IN	VARCHAR2,
	vLocationType			IN	VARCHAR2,
	nMailable				IN	NUMBER,
	nLivable				IN	NUMBER,
	nContactID				IN	NUMBER,
	vMessage				OUT	VARCHAR2)
	 IS

	myStreetID1				NUMBER;
	myStreetID2				NUMBER;
	myStreetAddressID1		NUMBER;
	myStreetAddressID2		NUMBER;
	myTownID1				NUMBER;
	myTownID2				NUMBER;
	myTownshipID			NUMBER;
	myAddressJurisdictionID NUMBER;
	myGovtJurisdictionID	NUMBER;
	myLocationID			NUMBER;
	myLocationTypeID		NUMBER;
	address1_already_exists	EXCEPTION;
	address2_already_exists	EXCEPTION;
	street1_not_found 	EXCEPTION;
	street2_not_found 	EXCEPTION;
	myNum					NUMBER;
	errorblock				CHAR(1);

	BEGIN

	    errorblock:='A';
    		-- Find the town 1
 		IF vTown1 IS NOT NULL THEN
			SELECT town_id INTO myTownID1 FROM eng.towns_master WHERE UPPER(description) = UPPER(vTown1);
	    ELSE
	    	myTownID1 := NULL;
		END IF;

	    errorblock:='B';
    		-- Find the town 2

 		IF vTown2 IS NOT NULL THEN
			SELECT town_id INTO myTownID2 FROM eng.towns_master WHERE UPPER(description) = UPPER(vTown2);
	    ELSE
	    	myTownID2 := NULL;
		END IF;


		    -- Find the street1
		errorblock:='C';
		SELECT COUNT(s.street_id) INTO myNum FROM eng.mast_street s, eng.mast_street_names n
			WHERE s.street_id = n.street_id
			  AND UPPER(n.street_name) = UPPER(vStreetName1)
			  AND (s.town_id = myTownID1 OR s.town_id IS NULL)
			  AND n.street_direction_code = cStreet_direction1
			  AND NVL(n.post_direction_suffix_code,'XXX') = NVL(cPost_direction1,'XXX')
			  AND (s.status_code = 1 OR s.status_code = 3)
			  AND NVL(n.street_type_suffix_code,'XXX') = NVL(vStreetSuffix1,'XXX')
			  AND n.street_name_type = 'STREET';
		IF myNum = 0 THEN RAISE street1_not_found;
		END IF;

		-- Generate the street ID  1
		errorblock:='D';
		SELECT s.street_id INTO myStreetID1 FROM eng.mast_street s, eng.mast_street_names n
			WHERE s.street_id = n.street_id
			  AND UPPER(n.street_name) = UPPER(vStreetName1)
			  AND (s.town_id = myTownID1 OR s.town_id IS NULL)
			  AND n.street_direction_code = cStreet_direction1
			  AND NVL(n.post_direction_suffix_code,'XXX') = NVL(cPost_direction1,'XXX')
			  AND (s.status_code = 1 OR s.status_code = 2)
			  AND NVL(n.street_type_suffix_code,'XXX') = NVL(vStreetSuffix1,'XXX')
			  --AND n.street_name_type = 'STREET';
			  AND n.street_name_type != 'ALIAS';


		    -- Find the street 2
		errorblock:='E';
		SELECT COUNT(s.street_id) INTO myNum FROM eng.mast_street s, eng.mast_street_names n
			WHERE s.street_id = n.street_id
			  AND UPPER(n.street_name) = UPPER(vStreetName2)
			  AND (s.town_id = myTownID2 OR s.town_id IS NULL)
			  AND n.street_direction_code = cStreet_direction2
			  AND NVL(n.post_direction_suffix_code,'XXX') = NVL(cPost_direction2,'XXX')
			  AND s.status_code = 1
			  AND NVL(n.street_type_suffix_code,'XXX') = NVL(vStreetSuffix2,'XXX')
			  AND n.street_name_type = 'STREET';
		IF myNum = 0 THEN RAISE street2_not_found;
		END IF;

		-- Generate the street ID  2
		errorblock:='F';
		SELECT s.street_id INTO myStreetID2 FROM eng.mast_street s, eng.mast_street_names n
			WHERE s.street_id = n.street_id
			  AND UPPER(n.street_name) = UPPER(vStreetName2)
			  AND (s.town_id = myTownID2 OR s.town_id IS NULL)
			  AND n.street_direction_code = cStreet_direction2
			  AND NVL(n.post_direction_suffix_code,'XXX') = NVL(cPost_direction2,'XXX')
			  AND s.status_code = 1
			  AND NVL(n.street_type_suffix_code,'XXX') = NVL(vStreetSuffix2,'XXX')
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


		-- Check to see if address 1 already exists
		-- in future, address_type may be part of key so we can have duplicate numbers
		errorblock:='J';
		SELECT COUNT(*)
		  INTO myNum
		  FROM eng.mast_address
		 WHERE street_id = myStreetID1
		   AND street_number = vStreetNumber1;

		IF myNum > 0 THEN RAISE address1_already_exists;
		END IF;

		-- Check to see if address 2 already exists
		-- in future, address_type may be part of key so we can have duplicate numbers
		errorblock:='K';
		SELECT COUNT(*)
		  INTO myNum
		  FROM eng.mast_address
		 WHERE street_id = myStreetID2
		   AND street_number = vStreetNumber2;

		IF myNum > 0 THEN RAISE address2_already_exists;
		END IF;


		-- Find a new street address ID 1
		errorblock:='L';
		SELECT eng.street_address_id_s.NEXTVAL INTO myStreetAddressID1 FROM DUAL;


		-- Create the address 1
		errorblock:='M';
		INSERT INTO ENG.MAST_ADDRESS (STREET_ADDRESS_ID, STREET_NUMBER, STREET_ID, ADDRESS_TYPE,
			TAX_JURISDICTION, JURISDICTION_ID, GOV_JUR_ID, TOWNSHIP_ID, SECTION,
			QUARTER_SECTION, SUBDIVISION_ID, PLAT_ID, PLAT_LOT_NUMBER,
			STREET_ADDRESS_2, CITY, STATE, ZIP, ZIPPLUS4, NOTES)
		VALUES (myStreetAddressID1, vStreetNumber1, myStreetID1, 'STREET',
			vTaxJurisdiction, myAddressJurisdictionID, myGovtJurisdictionID, myTownshipID, vSection,
			vQuarterSection, nSubdivisionID, nPlatID, vPlatLotNumber,
			vStreetAddress2_1, vCity, 'IN', vZip, vZipPlus4,
			'CORNER LOT WITH ' ||vStreetNumber2||' '||cStreet_direction2||' '||vStreetName2||' '||vStreetSuffix2||' '||cPost_direction2);

		IF vNotes1 IS NOT NULL THEN
	    	UPDATE eng.mast_address set notes = notes || ' ' || vNotes1 where street_address_id = myStreetAddressID1;
		END IF;


		-- Find a new street address ID 2
		errorblock:='N';
		SELECT eng.street_address_id_s.NEXTVAL INTO myStreetAddressID2 FROM DUAL;


		-- Create the address 2
		errorblock:='O';
		INSERT INTO ENG.MAST_ADDRESS (STREET_ADDRESS_ID, STREET_NUMBER, STREET_ID, ADDRESS_TYPE,
			TAX_JURISDICTION, JURISDICTION_ID, GOV_JUR_ID, TOWNSHIP_ID, SECTION,
			QUARTER_SECTION, SUBDIVISION_ID, PLAT_ID, PLAT_LOT_NUMBER,
			STREET_ADDRESS_2, CITY, STATE, ZIP, ZIPPLUS4, NOTES)
		VALUES (myStreetAddressID2, vStreetNumber2, myStreetID2, 'STREET',
			vTaxJurisdiction, myAddressJurisdictionID, myGovtJurisdictionID, myTownshipID, vSection,
			vQuarterSection, nSubdivisionID, nPlatID, vPlatLotNumber,
			vStreetAddress2_2, vCity, 'IN', vZip, vZipPlus4,
		   'CORNER LOT WITH ' ||vStreetNumber1||' '||cStreet_direction1||' '||vStreetName1||' '||vStreetSuffix1||' '||cPost_direction1);

		IF vNotes2 IS NOT NULL THEN
	    	UPDATE eng.mast_address set notes = notes || ' ' || vNotes1 where street_address_id = myStreetAddressID2;
		END IF;



		-- Create Locaation - Only 1 Location is needed maake first active and second inactive

	   errorblock:='P';
	   SELECT eng.location_id_s.nextval INTO myLocationID FROM DUAL;

	   INSERT INTO eng.address_location (location_id, location_type_id, street_address_id,
	   		mailable_flag,livable_flag, active)
	   VALUES (myLocationID, vLocationType ,myStreetAddressID1, nMailable, nLivable, 'Y');

	   INSERT INTO eng.address_location (location_id, location_type_id, street_address_id,
	   		mailable_flag,livable_flag, active)
	   VALUES (myLocationID, vLocationType ,myStreetAddressID2, nMailable, nLivable, 'N');

		-- Create the status for the location
		errorblock := 'Q';
		INSERT INTO eng.mast_address_location_status (status_code, effective_start_date, location_id)
		 VALUES (1, SYSDATE, myLocationID);

		-- Create the status for the address 1
		errorblock := 'R';
		INSERT into eng.mast_address_status (status_code, start_date, street_address_id)
		 VALUES (1, SYSDATE, myStreetAddressID1);

		-- Create the status for the  address 2
		errorblock := 'S';
		INSERT into eng.mast_address_status (status_code, start_date, street_address_id)
		 VALUES (1, SYSDATE, myStreetAddressID2);




		-- Update Location Assignment History
		-- Note that we defaulted the contact to NULL
		errorblock:='T';

		-- First address
		INSERT INTO eng.mast_address_assignment_hist (location_id, action_date, action, contact_id, notes, street_address_id)
		 VALUES (myLocationID, SYSDATE, 'ASSIGN', nContactID, 'CORNER LOT', myStreetAddressID1);
		-- now the second address
	   INSERT INTO eng.mast_address_assignment_hist (location_id, action_date, action, contact_id, notes, street_address_id)
		 VALUES (myLocationID, SYSDATE, 'ASSIGN', nContactID, 'CORNER LOT', myStreetAddressID2);


		-- Generate the status message

		vMessage:='Corner lots '||vStreetNumber1||' '||cStreet_direction1||' '||vStreetName1||' '||vStreetSuffix1||' '||cPost_direction1||
		' and '|| vStreetNumber2|| ' ' ||cStreet_direction2||' '||vStreetName2||' '||vStreetSuffix2||' '||cPost_direction2||
		' -Address IDs '||myStreetAddressID1|| ' ' ||myStreetAddressID2|| ' - Location ID '||myLocationID;

	COMMIT;

	EXCEPTION
		WHEN address1_already_exists THEN
			vMessage:=vStreetNumber1||' '||cStreet_direction1||' '||vStreetName1||' '||vStreetSuffix1||' '||cPost_direction1||' address1 already exists';
		WHEN address2_already_exists THEN
			vMessage:=vStreetNumber2||' '||cStreet_direction2||' '||vStreetName2||' '||vStreetSuffix2||' '||cPost_direction2||' address2 already exists';
		WHEN street1_not_found THEN
			vMessage:=cStreet_direction1||' '||vStreetName1||' '||vStreetSuffix1||' '||cPost_direction1||' street1 not found';
		WHEN street2_not_found THEN
			vMessage:=cStreet_direction2||' '||vStreetName2||' '||vStreetSuffix2||' '||cPost_direction2||' street2 not found';
		WHEN no_data_found THEN
			vMessage:='Some other error has occurred in block '||errorblock;
	END;
