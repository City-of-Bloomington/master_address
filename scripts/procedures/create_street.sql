create or replace PROCEDURE                      create_street (
	cStreet_direction	IN	CHAR,
	vStreetName			IN	VARCHAR2,
	vStreetSuffix		IN	VARCHAR2,
	cPost_direction		IN	CHAR,
	vTown				IN	VARCHAR2,
	vMessage			OUT	VARCHAR2) IS

	myStreetID			NUMBER;
	myTownID			NUMBER;
	myNumStreets		NUMBER;
	street_already_exists EXCEPTION;

	BEGIN

		-- Checks for duplicates
		-- does not consider towns or aliases
		SELECT COUNT(*) INTO myNumStreets FROM eng.mast_street_names msn WHERE
			street_name = vStreetName
			AND NVL(street_type_suffix_code,'XXX') = vStreetSuffix
			AND street_direction_code = cStreet_direction
			AND post_direction_suffix_code = cPost_direction;
		IF myNumStreets > 0 THEN RAISE street_already_exists;
		END IF;

		-- Generate the new script ID
		SELECT eng.street_id_s.NEXTVAL INTO myStreetID FROM DUAL;

		-- Find the town
 		IF vTown IS NOT NULL THEN
			SELECT town_id INTO myTownID FROM eng.towns_master WHERE UPPER(description) = UPPER(vTown);
	    ELSE
	    	myTownID := NULL;
		END IF;

		-- This script should create a new street

		INSERT INTO eng.mast_street (street_id, town_id, status_code, notes)
		 VALUES (myStreetID, myTownID, 1, NULL);

		INSERT INTO eng.mast_street_names (street_id, street_direction_code, street_name, street_type_suffix_code,
		 post_direction_suffix_code, street_name_type, effective_start_date)
		 VALUES (myStreetID, cStreet_direction, vStreetName, vStreetSuffix, cPost_direction, 'STREET', SYSDATE);

		COMMIT;
		vMessage:=cStreet_direction||' '||vStreetName||' '||vStreetSuffix||' '||cPost_direction||' ' ||vTown||' successfully added, streetID' || myStreetID;

		EXCEPTION
			WHEN street_already_exists THEN
				vMessage:=cStreet_direction||' '||vStreetName||' '||vStreetSuffix||' '||cPost_direction||' already exists';

	END;
