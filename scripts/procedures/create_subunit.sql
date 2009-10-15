create or replace PROCEDURE                                                                create_subunit (
	vStreetNumber		IN	VARCHAR2,
	cStreet_direction	IN	CHAR,
	vStreetName			IN	VARCHAR2,
	vStreetSuffix		IN	VARCHAR2,
	cPost_direction		IN	CHAR,
	vTown				IN	VARCHAR2,
	vSUDType				IN	VARCHAR2,
	vSubunitIdentifier		IN	VARCHAR2,
	vNotes					IN	VARCHAR2,
	vLocationTypeID			IN	VARCHAR2,
	nMailable				IN	NUMBER,
	nLivable				IN	NUMBER,
	nContactID				IN	NUMBER,
	vAssignmentNotes		IN	VARCHAR2,
	vMessage				OUT	VARCHAR2)
    IS

	myTownID				NUMBER;
	mySubunitID				NUMBER;
	myStreetID				NUMBER;
	myStreetAddressID		NUMBER;
	myLocationID			NUMBER;
	myLocationTypeID		NUMBER;
	subunit_already_exists	EXCEPTION;
	address_not_found 		EXCEPTION;
	street_not_found 		EXCEPTION;
	myNum					NUMBER;
	errorblock				CHAR(1);


	BEGIN

		-- Find the town
       errorblock := 'A';

 		IF vTown IS NOT NULL THEN
			SELECT town_id INTO myTownID FROM eng.towns_master WHERE UPPER(description) = UPPER(vTown);
	    ELSE
	    	myTownID := NULL;
		END IF;


		    -- Find the street
		errorblock:='B';

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

		-- Generate the street ID
		errorblock:='D';
		SELECT s.street_id INTO myStreetID FROM eng.mast_street s, eng.mast_street_names n
			WHERE s.street_id = n.street_id
			  AND UPPER(n.street_name) = UPPER(vStreetName)
			  AND (s.town_id = myTownID OR s.town_id IS NULL)
			  AND n.street_direction_code = cStreet_direction
			  AND NVL(n.post_direction_suffix_code,'XXX') = NVL(cPost_direction,'XXX')
			  AND s.status_code = 1
			  AND NVL(n.street_type_suffix_code,'XXX') = NVL(vStreetSuffix,'XXX')
			  AND n.street_name_type = 'STREET';


		-- Check to see if the address already exists
		errorblock:='E';
		SELECT COUNT(*)
		  INTO myNum
		  FROM eng.mast_address
		 WHERE street_id = myStreetID
		   AND street_number = vStreetNumber;

		IF myNum = 0 THEN RAISE address_not_found;
		END IF;


        -- Get street address ID
		errorblock:='F';

 		SELECT s.street_address_id
 		  INTO myStreetAddressID
 		  FROM eng.mast_address s
		 WHERE street_id = myStreetID
		   AND street_number = vStreetNumber;


		-- Check to see if the subunit already exists
		errorblock:='G';
		SELECT COUNT(*)
		  INTO myNum
		  FROM eng.mast_address_subunits
		 WHERE street_address_id = myStreetAddressID
		   AND sudtype = vSUDType
		   AND street_subunit_identifier = vSubunitIdentifier;

		IF myNum > 0 THEN RAISE subunit_already_exists;
		END IF;


		-- Get next SubunitID
		errorblock:='H';

   		SELECT eng.subunit_id_s.NEXTVAL INTO mySubunitID FROM DUAL;


  		-- Create subunit record
   		errorblock := 'I';

   		INSERT INTO ENG.MAST_ADDRESS_SUBUNITS (SUBUNIT_ID, STREET_ADDRESS_ID, SUDTYPE, STREET_SUBUNIT_IDENTIFIER,
	   		NOTES)
		VALUES (mySubunitID, myStreetAddressID, vSUDType, vSubunitIdentifier, vNotes);

		-- Create the Location
   		errorblock := 'J';

   		SELECT eng.location_id_s.NEXTVAL INTO myLocationID FROM DUAL;

   		INSERT INTO eng.address_location (location_id, location_type_id, street_address_id, subunit_id,
			mailable_flag, livable_flag, active)
		VALUES (myLocationID, vLocationTypeID, myStreetAddressID, mySubunitID,
			nMailable, nLivable,'Y');

		-- Create Location Assignment History
		-- Note that we defaulted the contact to NULL
		errorblock := 'K';

		INSERT INTO eng.mast_address_assignment_hist (location_id, action_date, action, contact_id, notes, street_address_id, subunit_id)
		 VALUES (myLocationID, SYSDATE, 'ASSIGN', nContactID, vAssignmentNotes, myStreetAddressID, mySubunitID);

		vMessage:=vStreetNumber||' '||cStreet_direction||' '||vStreetName||' '||vStreetSuffix||' '||cPost_direction||' ' ||vSUDType||' '||vSubunitIdentifier||' successfully added as Location ID '||myLocationID;

		-- Create the status for the location
		errorblock := 'L';
		INSERT INTO eng.mast_address_location_status (status_code, effective_start_date, location_id)
		 VALUES (1, SYSDATE, myLocationID);

		-- Create the status for the subunit
		errorblock := 'M';
		INSERT into eng.mast_address_subunit_status (subunit_id, street_address_id, status_code, start_date)
		 VALUES (mySubunitID, myStreetAddressID, 1, SYSDATE);

		COMMIT;

		-- Generate the status message

		vMessage:=vStreetNumber||' '||cStreet_direction||' '||vStreetName||' '||vStreetSuffix||' '||cPost_direction||' '||vSUDType||' '||vSubunitIdentifier||' successfully added as Location ID '||myLocationID;

	EXCEPTION
		WHEN address_not_found THEN
			vMessage:=vStreetNumber||' '||cStreet_direction||' '||vStreetName||' '||vStreetSuffix||' '||cPost_direction||' not found';
		WHEN street_not_found THEN
			vMessage:=cStreet_direction||' '||vStreetName||' '||vStreetSuffix||' '||cPost_direction||' not found';
		WHEN subunit_already_exists THEN
			vMessage:=vStreetNumber||' '||cStreet_direction||' '||vStreetName||' '||vStreetSuffix||' '||cPost_direction||' '||vSUDType||' '||vSubunitIdentifier||' already exists';
		WHEN no_data_found THEN
			vMessage:='Some other error has occurred';



	END;
