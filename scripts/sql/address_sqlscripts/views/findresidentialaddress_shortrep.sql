select LOCATION_STATUS "LSTATUS", ACTIVE, ADDRESS_STATUS "ASTATUS", SUBNIT_STAUTS "SSTATUS",
STREET_NUMBER "NUMBER", STREET_DIRECTION "DIR", STREET_NAME, STREET_SUFFIX "SUFFIX",
POST_DIRECTION "POSTDIR", SUDTYPE, SUDNUM, ZIP, LOCATION_CLASS, ADDRESS_TYPE, GOV_JUR_ID
from gis.residential_location_view
where STREET_NUMBER like '&STREET_NUMBER'
and (STREET_DIRECTION like '&DIRECTION' or STREET_DIRECTION is null)
and STREET_NAME like '&STREET_NAME'
and (STREET_SUFFIX like '&SUFFIX' or STREET_SUFFIX is null)
and gov_jur_id = '1'
order by street_name, street_suffix, street_direction, post_direction, city, street_number asc
