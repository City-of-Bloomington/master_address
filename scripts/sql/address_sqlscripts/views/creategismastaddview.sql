create or replace view gis.gismastaddview as
select
    al.location_id,
    al.street_address_id,
    al.subunit_id,
    al.active,
    lstat.description              as location_status,
    astat.description              as address_status,
    mass.status_code               as subunit_status,
    mas.start_date,
    mas.end_date,
    bal.building_id,
    b.gis_tag,
    bsl.description                as building_status,
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
    gov.description                as jurisdiction,
    ma.city,
    ma.zip,
    ma.zipplus4,
    plat.name                      as plat,
    ma.plat_lot_number             as lot,
    ma.street_address_2,
    ma.state_plane_x_coordinate,
    ma.state_plane_y_coordinate,
    ma.latitude,
    ma.longitude,
    ma.notes,
    ma.street_number                             || ' '
        || rtrim(msn.street_direction_code)      || ' '
        || msn.street_name                       || ' '
        || msn.street_type_suffix_code           || ' '
        || rtrim(msn.post_direction_suffix_code) || ' '
        || masu.sudtype                          || ' '
        || masu.subunit_identifier                                      as addr_line1,
    ma.city || ', ' || ma.state || '  ' || ma.zip || '-' || ma.zipplus4 as addr_line2

from      eng.address_location               al
     join eng.mast_address                   ma    on al.street_address_id = ma.street_address_id
left join eng.mast_address_subunits          masu  on al.subunit_id = masu.subunit_id and al.street_address_id = masu.street_address_id
     join eng.mast_address_location_status   als   on al.location_id = als.location_id
     join eng.mast_address_status            mas   on ma.street_address_id = mas.street_address_id
left join eng.mast_address_subunit_status    mass  on al.subunit_id = mass.subunit_id
     join eng.mast_address_status_lookup     lstat on als.status_code = lstat.status_code
     join eng.mast_address_status_lookup     astat on mas.status_code = astat.status_code
     join eng.mast_street                    ms    on ma.street_id = ms.street_id
     join eng.mast_street_names              msn   on ms.street_id = msn.street_id and msn.street_name_type = 'STREET'
left join eng.towns_master                   tm    on ms.town_id = tm.town_id
     join eng.governmental_jurisdiction_mast gov   on ma.gov_jur_id = gov.gov_jur_id
left join eng.plat_master                    plat  on ma.plat_id = plat.plat_id
left join gis.building_address_location      bal   on al.location_id = bal.location_id
left join gis.buildings                      b     on bal.building_id = b.building_id
left join gis.buildings_status_lookup        bsl   on b.status_code = bsl.status_code;
