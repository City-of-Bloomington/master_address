create or replace view gis.address_purposes as
select
    al.location_id,
    al.street_address_id,
    al.subunit_id,
    lp.location_purpose_id,
    lpm.description,
    lpm.type,
    bal.building_id,
    b.gis_tag,
    ma.street_number               as street_number,
    msn.street_direction_code      as street_direction,
    msn.street_name                as street_name,
    msn.street_type_suffix_code    as street_suffix,
    msn.post_direction_suffix_code as post_direction,
    masu.sudtype,
    masu.subunit_identifier
from      eng.address_location             al
     join eng.mast_address                 ma   on al.street_address_id = ma.street_address_id
left join eng.mast_address_subunits        masu on ma.street_address_id = masu.street_address_id and al.subunit_id = masu.subunit_id
left join eng.addr_location_purposes       lp   on al.location_id = lp.location_id
left join eng.addr_location_purpose_mast   lpm  on lp.location_purpose_id = lpm.location_purpose_id
     join eng.mast_address_location_status als  on al.location_id = als.location_id
     join eng.mast_address_status          mas  on ma.street_address_id = mas.street_address_id
     join eng.mast_street                  ms   on ma.street_id = ms.street_id
     join eng.mast_street_names            msn  on ms.street_id = msn.street_id and msn.street_name_type = 'STREET'
left join gis.building_address_location    bal  on al.location_id = bal.location_id
left join gis.buildings                    b    on bal.building_id = b.building_id;
