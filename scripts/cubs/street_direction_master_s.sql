create materialized view street_direction_master_s
tablespace "CUBSD"
refresh complete on demand start with sysdate+0 next trunc(sysdate+1) + (5.7/24)
disable query rewrite
as
select distinct direction_code,description as direction_description
from eng.mast_street_direction_master@earthgis.world;
