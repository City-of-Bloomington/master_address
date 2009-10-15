create or replace PROCEDURE     create_location_purpose (
	vLocationPurpose	IN	VARCHAR2,
	vType				IN	VARCHAR2,
	vMessage			OUT	VARCHAR2)
	IS

	myLocationPurposeID	NUMBER;
	myNumPurposes		NUMBER;
	purpose_already_exists EXCEPTION;

	BEGIN

		-- Checks for duplicates
		SELECT COUNT(*) INTO myNumPurposes FROM eng.addr_location_purpose_mast WHERE
			description = vLocationPurpose ;
		IF myNumPurposes > 0 THEN RAISE purpose_already_exists;
		END IF;

		-- Generate the new location purpose ID
		SELECT eng.location_purpose_id_s.NEXTVAL INTO myLocationPurposeID FROM DUAL;

		-- This script should create a new street

		INSERT INTO eng.addr_location_purpose_mast (location_purpose_id, description, type)
		 VALUES (myLocationPurposeID, vLocationPurpose, vType);

		COMMIT;
		vMessage:=vLocationPurpose|| ' Location PurposeID ' ||myLocationPurposeID|| ' successfully added';

		EXCEPTION
			WHEN purpose_already_exists THEN
				vMessage:=vLocationPurpose||' already exists';

	END;
