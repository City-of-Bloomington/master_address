create materialized view street_names_master_s
tablespace "CUBSD"
refresh complete on demand start with sysdate+0 next trunc(sysdate+1) + (5.5/24)
disable query rewrite
as
select distinct street_name,street_type_suffix_code
from eng.mast_street_names@earthgis.world
union all
select 'Temporary Meter Location', 'BYP' from dual ;