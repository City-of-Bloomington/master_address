
create materialized view cubs.ce_address_v
tablespace CUBSD
refresh complete on demand start with sysdate+0 next trunc(sysdate+1) + (4.6/24)
with rowid disable query rewrite
as
select address.street_num street_num,address.street_dir street_dir,address.street_name street_name,
address.street_type street_type,address.post_dir post_dir,address.sud_type sud_type,
address.sud_num sud_num,address.invalid_addr invalid_addr,address.registr_id registr_id
from ce.address@earthgis.world address;



create materialized view cubs.ce_name_v
tablespace CUBSD
refresh complete on demand start with sysdate+0 next trunc(sysdate+1) + (4.3/24)
with primary key disable query rewrite
as
select name.name_num name_num,name.name name,name.address address,name.city city,
name.state state,name.zip zip,name.phone_work phone_work,name.phone_home phone_home,
name.notes notes,name.email email
from ce.name@earthgis.world name;


create materialized view cubs.ce_regid_name_v
tablespace CUBSD
refresh complete on demand start with sysdate+0 next trunc(sysdate+1) + (4.4/24)
with primary key disable query rewrite
as select regid_name.name_num name_num,regid_name.id id
from ce.regid_name@earthgis.world regid_name;


create materialized view cubs.ce_registr_v
tablespace CUBSD
refresh complete on demand start with sysdate+0 next trunc(sysdate+1) + (4.5/24)
with primary key disable query rewrite
as
select registr.id id,registr.property_status property_status,registr.agent agent,
registr.registered_date registered_date,registr.last_cycle_date last_cycle_date,
registr.permit_issued permit_issued,registr.permit_expires permit_expires,
registr.permit_length permit_length,registr.pull_date pull_date,registr.pull_reason pull_reason,
registr.new_rental new_rental,registr.zoning zoning,registr.grandfathered grandfathered,
registr.annexed annexed,registr.units units,registr.structures structures,
registr.bedrooms bedrooms,registr.occ_load occ_load,registr.date_billed date_billed,
registr.date_rec date_rec,registr.notes notes,registr.cdbg_funding cdbg_funding,
registr.zoning2 zoning2
from ce.registr@earthgis.world registr;

