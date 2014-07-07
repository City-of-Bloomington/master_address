select distinct
    masan.street_address_id,
    g.gis_tag,
    g.street_number                      as "number",
    g.street_direction                   as dir,
    g.street_name                        as street_name ,
    g.street_suffix                      as suffix,
    g.post_direction                     as postdir,
    g.sudtype||' '||g.subunit_identifier as subunit,
    g.city,
    g.state,
    g.zip,
    g.zipplus4,
    masan.trash_pickup_day,
    masan.recycle_week
from gis.gisaddress              g
join eng.mast_address_sanitation masan on g.street_address_id=masan.street_address_id
where (g.address_status = '1' and (g.subunit_status = '1' or g.subunit_status is null))
  and masan.trash_pickup_day like '&PICKUPDAY'
  and masan.recycle_week     like '&RECYCLEWEEK'
  -- need the following to get rid of extra entries that don't get trash pickup
  and masan.trash_pickup_day is not null
order by street_name, suffix, dir, postdir, "number", subunit;
