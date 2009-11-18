create materialized view street_names_master_s
tablespace "CUBSD"
refresh complete on demand start with sysdate+0 next trunc(sysdate+1) + (5.5/24)
disable query rewrite
as
select distinct upper(street_name) as street_name,
upper(street_type_suffix_code) as street_type_suffix_code
from eng.mast_street_names@earthgis.world
union all
select 'TEMPORARY METER LOCATION', 'BYP' from dual;