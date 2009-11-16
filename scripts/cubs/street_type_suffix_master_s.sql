create materialized view street_type_suffix_master_s
tablespace "CUBSD"
refresh complete on demand start with sysdate+0 next trunc(sysdate+1) + (5.6/24)
disable query rewrite
as
select distinct suffix_code,description as suffix_desc
from eng.mast_street_type_suffix_master@earthgis.world;
