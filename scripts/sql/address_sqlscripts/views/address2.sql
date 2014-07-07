create or replace view gis.gisaddress2 as
select
    al.location_id,
    al.street_address_id,
    al.subunit_id,
    al.active,
    als.status_code                as location_status,
    mas.status_code                as address_status,
    mass.status_code               as subunit_status,
    bal.building_id,
    b.gis_tag,
    ma.street_number               as street_number,
    msn.street_direction_code      as street_direction,
    msn.street_name                as street_name,
    msn.street_type_suffix_code    as street_suffix,
    msn.post_direction_suffix_code as post_direction,
    masu.sudtype,
    masu.subunit_identifier,
    ma.street_id,
    tm.description                 as town,
    ma.address_type,
    ma.addr_jurisdiction_id,
    ma.gov_jur_id,
    ma.township_id,
    ma.section,
    ma.quarter_section,
    ma.city,
    ma.state,
    ma.zip,
    ma.zipplus4,
    ma.subdivision_id,
    ma.plat_id,
    ma.plat_lot_number,
    ma.street_address_2,
    ma.state_plane_x_coordinate,
    ma.state_plane_y_coordinate,
    ma.latitude,
    ma.longitude,
    ma.street_number                             || ' '
        || rtrim(msn.street_direction_code)      || ' '
        || msn.street_name                       || ' '
        || msn.street_type_suffix_code           || ' '
        || rtrim(msn.post_direction_suffix_code) || ' '
        || masu.sudtype                          || ' '
        || masu.subunit_identifier                                      as addr_line1,
    ma.city || ', ' || ma.state || '  ' || ma.zip || '-' || ma.zipplus4 as addr_line2

from      eng.address_location             al
     join eng.mast_address                 ma   on al.street_address_id = ma.street_address_id
left join eng.mast_address_subunits        masu on al.street_address_id = masu.street_address_id and al.subunit_id = masu.subunit_id
     join eng.mast_address_location_status als  on al.location_id = als.location_id
     join eng.mast_address_status          mas  on ma.street_address_id = mas.street_address_id
left join eng.mast_address_subunit_status  mass on al.subunit_id = mass.subunit_id
     join eng.mast_street                  ms   on ma.street_id = ms.street_id
     join eng.mast_street_names            msn  on ms.street_id = msn.street_id and msn.street_name_type = 'STREET'
left join eng.towns_master                 tm   on ms.town_id = tm.town_id
left join gis.building_address_location    bal  on al.location_id = bal.location_id
left join gis.buildings                    b    on bal.building_id = b.building_id;
