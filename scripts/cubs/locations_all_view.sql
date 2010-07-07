-- Old One
  CREATE OR REPLACE FORCE VIEW CUBS.LOCATIONS_ALL_VIEW (LOCATION_ID, LOCATION_TYPE_ID, STREET_ADDRESS_ID, FULL_STREET_NAME, FULL_STREET_ADDRESS, FULL_STREET_ADDRESS_SUD, SUBUNIT_ID, MAILABLE_FLAG, LIVABLE_FLAG, STREET_NUMBER, STREET_ID, ADDRESS_TYPE, TAX_JURISDICTION, JURISDICTION_ID, GOV_JUR_ID, TOWNSHIP_ID, SECTION, QUARTER_SECTION, SUBDIVISION_ID, PLAT_ID, PLAT_LOT_NUMBER, STREET_ADDRESS_2, CITY, STATE, ZIP, ZIPPLUS4, CENSUS_BLOCK_FIPS_CODE, STATE_PLANE_X_COORDINATE, STATE_PLANE_Y_COORDINATE, LATITUDE, LONGITUDE, ADDRESS_NOTES, STREET_DIRECTION_CODE, POST_DIRECTION_SUFFIX_CODE, TOWN_ID, STATUS_CODE, STREET_NOTES, STREET_NAME, STREET_TYPE_SUFFIX_CODE, STREET_NAME_TYPE, EFFECTIVE_START_DATE, EFFECTIVE_END_DATE, STREET_NAME_NOTES, SUBUNIT_TYPE, SUBUNIT, SUBUNIT_NOTES, LOC_ACTIVE_FLAG, LOCATION, ADDRESS_STATUS_CODE, ADDRESS_STATUS, LOCATION_STATUS_CODE, LOCATION_STATUS, SUBUNIT_STATUS_CODE, SUBUNIT_STATUS, INCITY) AS
  SELECT
        loc.location_id, loc.location_type_id, loc.street_address_id,
        TRIM(TRIM(names.street_direction_code)||decode(names.street_direction_code, NULL, '',' ')||names.street_name||' '||TRIM(names.street_type_suffix_code)||' '||street.post_direction_suffix_code) as full_street_name,
        TRIM(addr.street_number||' '||TRIM(names.street_direction_code)||decode(names.street_direction_code, NULL, '',' ')||names.street_name||' '||TRIM(names.street_type_suffix_code)||DECODE(street.post_direction_suffix_code,NULL,'',' ')||street.post_direction_suffix_code) as full_street_address,
        TRIM(addr.street_number||' '||TRIM(names.street_direction_code)||decode(names.street_direction_code, NULL, '',' ')||names.street_name||' '||TRIM(names.street_type_suffix_code)||DECODE(street.post_direction_suffix_code,NULL,'',' ')||street.post_direction_suffix_code||' '||suds.sudtype||' '||suds.street_subunit_identifier) as full_street_address_sud,
		loc.subunit_id,
		loc.mailable_flag, loc.livable_flag, addr.street_number, addr.street_id, addr.address_type,
		addr.tax_jurisdiction, addr.jurisdiction_id, addr.gov_jur_id,
		addr.township_id, addr.section, addr.quarter_section,
		addr.subdivision_id, addr.plat_id, addr.plat_lot_number, addr.street_address_2,
		addr.city, addr.state, addr.zip, addr.zipplus4, addr.census_block_fips_code,
		addr.state_plane_x_coordinate, addr.state_plane_y_coordinate, addr.latitude,
		addr.longitude, addr.notes as address_notes, TRIM(names.street_direction_code) AS street_direction_code,
		street.post_direction_suffix_code, street.town_id, street.status_code,
		street.notes as street_notes, names.street_name, names.street_Type_suffix_code,
		names.street_name_type, names.effective_start_date, names.effective_end_date,
		names.notes as street_name_notes,
		suds.sudtype, suds.street_subunit_identifier, suds.notes as subunit_notes,
		loc.active,
		TRIM(addr.street_number||' '||TRIM(names.street_direction_code)||decode(names.street_direction_code, NULL, '',' ')||names.street_name||' '||TRIM(names.street_type_suffix_code)||DECODE(street.post_direction_suffix_code,NULL,'',' ')||street.post_direction_suffix_code||' '||suds.sudtype||' '||suds.street_subunit_identifier) as location,
		mastatus.status_code AS address_status_code,
		DECODE(mastatus.status_code,1,'CURRENT',2,'TEMPORARY',3,'RETIRED',4,'MARKED FOR DELETION',NULL,'NONE','UNKNOWN') AS address_status,
		locstatus.status_code AS location_status_code,
		DECODE(locstatus.status_code,1,'CURRENT',2,'TEMPORARY',3,'RETIRED',4,'MARKED FOR DELETION',NULL,'NONE','UNKNOWN') AS location_status,
		sudstatus.status_code AS subunit_status_code,
		DECODE(sudstatus.status_code,1,'CURRENT',2,'TEMPORARY',3,'RETIRED',4,'MARKED FOR DELETION',NULL,'NONE','UNKNOWN') AS subunit_status,
 		DECODE(addr.gov_jur_id,1,'Y','N')
from
	eng.mast_street@earthgis.world street,
	eng.mast_street_names@earthgis.world names,
	eng.mast_address_subunits@earthgis.world suds,
	eng.mast_address_status@earthgis.world mastatus,
	eng.mast_address_location_status@earthgis.world locstatus,
	eng.mast_address_subunit_status@earthgis.world sudstatus,
    eng.mast_address@earthgis.world addr,
    eng.address_location@earthgis.world loc
where loc.street_address_id = addr.street_address_id
  AND loc.subunit_id = sudstatus.subunit_id(+)
  AND loc.location_id = locstatus.location_id
  and addr.street_id = street.street_id
  and addr.street_id = names.street_id
  and loc.subunit_id = suds.subunit_id(+)
  AND loc.street_address_id = mastatus.street_address_id
UNION ALL
   SELECT t.location_id, 'UNKNOWN', 0 AS street_address_id,
        'TEMPORARY METER LOCATION' as full_street_name,
        TRIM(SUBSTR(meter_id,1,20))||' TEMPORARY METER LOCATION BYP' as full_street_address,
        TRIM(SUBSTR(meter_id,1,20))||' TEMPORARY METER LOCATION BYP' as full_street_address_sud,
		TO_NUMBER(NULL) AS subunit_id,
		0 AS mailable_flag, 0 AS livable_flag,
		SUBSTR(meter_id,1,20) AS street_number,
		TO_NUMBER(NULL) AS street_id,
		'STREET' AS address_type,
		NULL tax_jurisdiction, 1 jurisdiction_id, 1 gov_jur_id,
		TO_NUMBER(NULL) AS township_id,
		NULL AS section, NULL AS quarter_section,
		TO_NUMBER(NULL) AS subdivision_id, TO_NUMBER(NULL) AS plat_id, TO_NUMBER(NULL) AS plat_lot_number, NULL AS street_address_2,
		'BLOOMINGTON' AS city, 'IN' AS state, '47401' AS zip, '0000' AS zipplus4, NULL AS census_block_fips_code,
		TO_NUMBER(NULL) AS state_plane_x_coordinate, TO_NUMBER(NULL) AS state_plane_y_coordinate, TO_NUMBER(NULL) AS latitude,
		TO_NUMBER(NULL) AS longitude, NULL as address_notes, NULL AS street_direction_code,
		NULL AS post_direction_suffix_code, TO_NUMBER(NULL) AS town_id, 1 AS status_code,
		NULL AS street_notes, 'TEMPORARY METER LOCATION' AS street_name, 'BYP' AS street_Type_suffix_code,
		'STREET' AS street_name_type, TO_DATE(NULL) AS effective_start_date, TO_DATE(NULL) AS effective_end_date,
		NULL as street_name_notes,
		NULL sudtype, NULL AS street_subunit_identifier, NULL as subunit_notes,
		'Y' AS loc_active_flag,
		TRIM(SUBSTR(meter_id,1,20))||' TEMPORARY METER LOCATION BYP' as location,
		1 AS address_status_code,
		'CURRENT' AS address_status,
		1 AS location_status_code,
		'CURRENT' AS location_status,
		NULL AS subunit_status_code,
		'NONE' AS subunit_status,
		'Y'
   FROM	cubs.temporary_meter_locations t
UNION ALL
select lm.location_id, lm.location_type_id, lm.street_address_id,
        TRIM(TRIM(lm.street_direction_code)||decode(lm.street_direction_code, NULL, '',' ')||lm.street_name||' '||TRIM(lm.street_type_suffix_code)||' '||lm.post_direction_suffix_code) as full_street_name,
        TRIM(lm.street_number||' '||TRIM(lm.street_direction_code)||decode(lm.street_direction_code, NULL, '',' ')||lm.street_name||' '||TRIM(lm.street_type_suffix_code)||DECODE(lm.post_direction_suffix_code,NULL,'',' ')||lm.post_direction_suffix_code) as full_street_address,
        TRIM(lm.street_number||' '||TRIM(lm.street_direction_code)||decode(lm.street_direction_code, NULL, '',' ')||lm.street_name||' '||TRIM(lm.street_type_suffix_code)||DECODE(lm.post_direction_suffix_code,NULL,'',' ')||lm.post_direction_suffix_code||' '||lm.sudtype||' '||lm.street_subunit_identifier) as full_street_address_sud,
		lm.subunit_id,
		lm.mailable_flag, lm.livable_flag, lm.street_number, lm.street_id, lm.address_type,
		lm.tax_jurisdiction, lm.jurisdiction_id, lm.gov_jur_id,
		lm.township_id, lm.section, lm.quarter_section,
		lm.subdivision_id, lm.plat_id, lm.plat_lot_number, lm.street_address_2,
		lm.city, lm.state, lm.zip, lm.zipplus4, lm.census_block_fips_code,
		lm.state_plane_x_coordinate, lm.state_plane_y_coordinate, lm.latitude,
		lm.longitude, NULL as address_notes, TRIM(lm.street_direction_code) AS street_direction_code,
		lm.post_direction_suffix_code, lm.town_id, lm.status_code,
		NULL as street_notes, lm.street_name, lm.street_Type_suffix_code,
		lm.street_name_type, lm.effective_start_date, lm.effective_end_date,
		NULL as street_name_notes,
		lm.sudtype, lm.street_subunit_identifier, NULL as subunit_notes,
		'Y' AS active,
		TRIM(lm.street_number||' '||TRIM(lm.street_direction_code)||decode(lm.street_direction_code, NULL, '',' ')||lm.street_name||' '||TRIM(lm.street_type_suffix_code)||DECODE(lm.post_direction_suffix_code,NULL,'',' ')||lm.post_direction_suffix_code||' '||lm.sudtype||' '||lm.street_subunit_identifier) as location,
		1 AS address_status_code,
		DECODE(1,1,'CURRENT',2,'TEMPORARY',3,'RETIRED',4,'MARKED FOR DELETION',NULL,'NONE','UNKNOWN') AS address_status,
		1 AS location_status_code,
		DECODE(1,1,'CURRENT',2,'TEMPORARY',3,'RETIRED',4,'MARKED FOR DELETION',NULL,'NONE','UNKNOWN') AS location_status,
		1 AS subunit_status_code,
		DECODE(1,1,'CURRENT',2,'TEMPORARY',3,'RETIRED',4,'MARKED FOR DELETION',NULL,'NONE','UNKNOWN') AS subunit_status,
		DECODE(lm.gov_jur_id,1,'Y','N')
from cubs.locations_unmatched lm
;






-- New One

create or replace force view locations_all_view
(location_id, location_type_id, street_address_id,
full_street_name, full_street_address, full_street_address_sud,
subunit_id, mailable_flag, livable_flag, street_number, street_id,
address_type, tax_jurisdiction, jurisdiction_id, gov_jur_id, township_id,
section, quarter_section, subdivision_id, plat_id, plat_lot_number, street_address_2,
city, state, zip, zipplus4, census_block_fips_code,
state_plane_x_coordinate, state_plane_y_coordinate, latitude, longitude,
address_notes, street_direction_code, post_direction_suffix_code, town_id,
status_code, street_notes, street_name, street_type_suffix_code, street_name_type,
effective_start_date, effective_end_date, street_name_notes,
subunit_type, subunit, subunit_notes, loc_active_flag, location,
address_status_code, address_status, location_status_code, location_status,
subunit_status_code, subunit_status, incity)
as
SELECT
	loc.location_id, loc.location_type_id, loc.street_address_id,
	upper(TRIM(TRIM(names.street_direction_code)||decode(names.street_direction_code, NULL, '',' ')||names.street_name||' '||TRIM(names.street_type_suffix_code)||' '||street.post_direction_suffix_code)) as full_street_name,
	upper(TRIM(addr.street_number||' '||TRIM(names.street_direction_code)||decode(names.street_direction_code, NULL, '',' ')||names.street_name||' '||TRIM(names.street_type_suffix_code)||DECODE(street.post_direction_suffix_code,NULL,'',' ')||street.post_direction_suffix_code)) as full_street_address,
	upper(TRIM(addr.street_number||' '||TRIM(names.street_direction_code)||decode(names.street_direction_code, NULL, '',' ')||names.street_name||' '||TRIM(names.street_type_suffix_code)||DECODE(street.post_direction_suffix_code,NULL,'',' ')||street.post_direction_suffix_code||' '||suds.sudtype||' '||suds.street_subunit_identifier)) as full_street_address_sud,
	loc.subunit_id,
	decode(loc.mailable_flag,'yes',1,'no',0,'unknown',NULL,NULL) as mailable_flag,
	decode(loc.livable_flag,'yes',1,'no',0,'unknown',NULL,NULL) as livable_flag,
	upper(addr.street_number), addr.street_id, upper(addr.address_type),
	upper(addr.tax_jurisdiction), addr.jurisdiction_id, addr.gov_jur_id,
	addr.township_id, upper(addr.section), upper(addr.quarter_section),
	addr.subdivision_id, addr.plat_id, addr.plat_lot_number, upper(addr.street_address_2),
	upper(addr.city), upper(addr.state), to_char(addr.zip), addr.zipplus4, upper(addr.census_block_fips_code),
	addr.state_plane_x_coordinate, addr.state_plane_y_coordinate, addr.latitude,
	addr.longitude, addr.notes as address_notes, upper(TRIM(names.street_direction_code)) AS street_direction_code,
	upper(street.post_direction_suffix_code), street.town_id, upper(street.status_code),
	street.notes as street_notes, upper(names.street_name), upper(names.street_Type_suffix_code),
	upper(names.street_name_type), names.effective_start_date, names.effective_end_date,
	names.notes as street_name_notes,
	upper(suds.sudtype), upper(suds.street_subunit_identifier), suds.notes as subunit_notes,
	loc.active,
	upper(TRIM(addr.street_number||' '||TRIM(names.street_direction_code)||decode(names.street_direction_code, NULL, '',' ')||names.street_name||' '||TRIM(names.street_type_suffix_code)||DECODE(street.post_direction_suffix_code,NULL,'',' ')||street.post_direction_suffix_code||' '||suds.sudtype||' '||suds.street_subunit_identifier)) as location,
	mastatus.status_code AS address_status_code,
	DECODE(mastatus.status_code,1,'CURRENT',2,'TEMPORARY',3,'RETIRED',4,'MARKED FOR DELETION',NULL,'NONE','UNKNOWN') AS address_status,
	locstatus.status_code AS location_status_code,
	DECODE(locstatus.status_code,1,'CURRENT',2,'TEMPORARY',3,'RETIRED',4,'MARKED FOR DELETION',NULL,'NONE','UNKNOWN') AS location_status,
	sudstatus.status_code AS subunit_status_code,
	DECODE(sudstatus.status_code,1,'CURRENT',2,'TEMPORARY',3,'RETIRED',4,'MARKED FOR DELETION',NULL,'NONE','UNKNOWN') AS subunit_status,
	DECODE(addr.gov_jur_id,1,'Y','N')
from
	eng.mast_street@earthgis.world street,
	eng.mast_street_names@earthgis.world names,
	eng.mast_address_subunits@earthgis.world suds,
	eng.mast_address_status@earthgis.world mastatus,
	eng.mast_address_location_status@earthgis.world locstatus,
	eng.mast_address_subunit_status@earthgis.world sudstatus,
    eng.mast_address@earthgis.world addr,
    eng.address_location@earthgis.world loc
where loc.street_address_id = addr.street_address_id
  AND loc.subunit_id = sudstatus.subunit_id(+)
  AND loc.location_id = locstatus.location_id
  and addr.street_id = street.street_id
  and addr.street_id = names.street_id
  and loc.subunit_id = suds.subunit_id(+)
  AND loc.street_address_id = mastatus.street_address_id
UNION ALL
   SELECT t.location_id, 'UNKNOWN', 0 AS street_address_id,
        'TEMPORARY METER LOCATION' as full_street_name,
        TRIM(SUBSTR(meter_id,1,20))||' TEMPORARY METER LOCATION BYP' as full_street_address,
        TRIM(SUBSTR(meter_id,1,20))||' TEMPORARY METER LOCATION BYP' as full_street_address_sud,
		TO_NUMBER(NULL) AS subunit_id,
		NULL AS mailable_flag, NULL AS livable_flag,
		SUBSTR(meter_id,1,20) AS street_number,
		TO_NUMBER(NULL) AS street_id,
		'STREET' AS address_type,
		NULL tax_jurisdiction, 1 jurisdiction_id, 1 gov_jur_id,
		TO_NUMBER(NULL) AS township_id,
		NULL AS section, NULL AS quarter_section,
		TO_NUMBER(NULL) AS subdivision_id, TO_NUMBER(NULL) AS plat_id, to_char(NULL) AS plat_lot_number, NULL AS street_address_2,
		'BLOOMINGTON' AS city, 'IN' AS state, '47401' AS zip, '0000' AS zipplus4, NULL AS census_block_fips_code,
		TO_NUMBER(NULL) AS state_plane_x_coordinate, TO_NUMBER(NULL) AS state_plane_y_coordinate, TO_NUMBER(NULL) AS latitude,
		TO_NUMBER(NULL) AS longitude, NULL as address_notes, NULL AS street_direction_code,
		NULL AS post_direction_suffix_code, TO_NUMBER(NULL) AS town_id, '1' AS status_code,
		NULL AS street_notes, 'TEMPORARY METER LOCATION' AS street_name, 'BYP' AS street_Type_suffix_code,
		'STREET' AS street_name_type, TO_DATE(NULL) AS effective_start_date, TO_DATE(NULL) AS effective_end_date,
		NULL as street_name_notes,
		NULL sudtype, NULL AS street_subunit_identifier, NULL as subunit_notes,
		'Y' AS loc_active_flag,
		TRIM(SUBSTR(meter_id,1,20))||' TEMPORARY METER LOCATION BYP' as location,
		1 AS address_status_code,
		'CURRENT' AS address_status,
		1 AS location_status_code,
		'CURRENT' AS location_status,
		NULL AS subunit_status_code,
		'NONE' AS subunit_status,
		'Y'
   FROM	cubs.temporary_meter_locations t
UNION ALL
select lm.location_id, lm.location_type_id, lm.street_address_id,
	TRIM(TRIM(lm.street_direction_code)||decode(lm.street_direction_code, NULL, '',' ')||lm.street_name||' '||TRIM(lm.street_type_suffix_code)||' '||lm.post_direction_suffix_code) as full_street_name,
	TRIM(lm.street_number||' '||TRIM(lm.street_direction_code)||decode(lm.street_direction_code, NULL, '',' ')||lm.street_name||' '||TRIM(lm.street_type_suffix_code)||DECODE(lm.post_direction_suffix_code,NULL,'',' ')||lm.post_direction_suffix_code) as full_street_address,
	TRIM(lm.street_number||' '||TRIM(lm.street_direction_code)||decode(lm.street_direction_code, NULL, '',' ')||lm.street_name||' '||TRIM(lm.street_type_suffix_code)||DECODE(lm.post_direction_suffix_code,NULL,'',' ')||lm.post_direction_suffix_code||' '||lm.sudtype||' '||lm.street_subunit_identifier) as full_street_address_sud,
	lm.subunit_id,
	lm.mailable_flag, lm.livable_flag, lm.street_number, lm.street_id, lm.address_type,
	lm.tax_jurisdiction, lm.jurisdiction_id, lm.gov_jur_id,
	lm.township_id, lm.section, lm.quarter_section,
	lm.subdivision_id, lm.plat_id, to_char(lm.plat_lot_number) as plat_lot_number, lm.street_address_2,
	lm.city, lm.state, lm.zip, lm.zipplus4, lm.census_block_fips_code,
	lm.state_plane_x_coordinate, lm.state_plane_y_coordinate, lm.latitude,
	lm.longitude, NULL as address_notes, TRIM(lm.street_direction_code) AS street_direction_code,
	lm.post_direction_suffix_code, lm.town_id, to_char(lm.status_code),
	NULL as street_notes, lm.street_name, lm.street_Type_suffix_code,
	lm.street_name_type, lm.effective_start_date, lm.effective_end_date,
	NULL as street_name_notes,
	lm.sudtype, lm.street_subunit_identifier, NULL as subunit_notes,
	'Y' AS active,
	TRIM(lm.street_number||' '||TRIM(lm.street_direction_code)||decode(lm.street_direction_code, NULL, '',' ')||lm.street_name||' '||TRIM(lm.street_type_suffix_code)||DECODE(lm.post_direction_suffix_code,NULL,'',' ')||lm.post_direction_suffix_code||' '||lm.sudtype||' '||lm.street_subunit_identifier) as location,
	1 AS address_status_code,
	DECODE(1,1,'CURRENT',2,'TEMPORARY',3,'RETIRED',4,'MARKED FOR DELETION',NULL,'NONE','UNKNOWN') AS address_status,
	1 AS location_status_code,
	DECODE(1,1,'CURRENT',2,'TEMPORARY',3,'RETIRED',4,'MARKED FOR DELETION',NULL,'NONE','UNKNOWN') AS location_status,
	1 AS subunit_status_code,
	DECODE(1,1,'CURRENT',2,'TEMPORARY',3,'RETIRED',4,'MARKED FOR DELETION',NULL,'NONE','UNKNOWN') AS subunit_status,
	DECODE(lm.gov_jur_id,1,'Y','N')
from cubs.locations_unmatched lm
;
