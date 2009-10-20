-- Old One

  CREATE OR REPLACE FORCE VIEW "CUBS"."FOO_ADDR_LOCATION_V" ("LOCATION_ID", "LOCATION_TYPE_ID", "STREET_ADDRESS_ID", "SUBUNIT_ID", "MAILABLE_FLAG") AS
  SELECT "LOCATION_ID","LOCATION_TYPE_ID","STREET_ADDRESS_ID","SUBUNIT_ID","MAILABLE_FLAG"
FROM eng.address_location@earthgis.world
;




-- New One
create or replace force view foo_addr_location_v (
	location_id, location_type_id, street_address_id, subunit_id, mailable_flag
) as
select location_id,location_type_id,street_address_id,subunit_id,
	decode(mailable_flag,'yes',1,'no',0,'unknown',NULL,NULL) as mailable_flag
from eng.address_location@earthgis.world;