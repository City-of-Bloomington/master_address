create materialized view address_subunits_master_s
tablespace "CUBSD"
refresh complete on demand start with sysdate+0 next trunc(sysdate+1) + (5.8/24)
disable query rewrite
as
select distinct sudtype
from eng.mast_address_subunits@earthgis.world;
