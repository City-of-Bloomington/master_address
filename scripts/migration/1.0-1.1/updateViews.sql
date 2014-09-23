create or replace view gis.rnt_addr_all as
select distinct
    b.gis_tag                      as tag_num,
    location_id,
    street_address_id,
    subunit_id,
    ra.registr_id,
    r.property_status
from rental.address2                  ra
join rental.registr                   r   on ra.registr_id=r.id
join gis.building_address_location    bal using (location_id)
join gis.buildings                    b   using (building_id)
join eng.address_location             al  using (location_id, street_address_id, subunit_id)
join eng.mast_address_location_status als using (location_id)
join eng.mast_address                 ma  using (street_address_id)
join eng.mast_address_status          mas using (street_address_id)
where mas.status_code='1'
  and als.status_code='1';


create or replace view gis.rnt_addr_active as
select distinct
    b.gis_tag                      as tag_num,
    location_id,
    street_address_id,
    subunit_id,
    ra.registr_id,
    r.property_status
from rental.address2                  ra
join rental.registr                   r   on ra.registr_id=r.id
join gis.building_address_location    bal using (location_id)
join gis.buildings                    b   using (building_id)
join eng.address_location             al  using (location_id, street_address_id, subunit_id)
join eng.mast_address_location_status als using (location_id)
join eng.mast_address                 ma  using (street_address_id)
join eng.mast_address_status          mas using (street_address_id)
where mas.status_code='1'
  and als.status_code='1'
  and (r.property_status = 'R'
      or (r.property_status = 'V'
         and r.permit_expires is not NULL
         and r.permit_expires >= sysdate));

grant select on gis.rnt_addr_all    to public;
grant select on gis.rnt_addr_active to public;
