create or replace view gis.residential_location_view as
select
    al.location_id,
    al.location_type_id,
    als.status_code                as location_status,
    al.active,
    al.street_address_id,
    mas.status_code                as address_status,
    al.subunit_id,
    masus.status_code              as subnit_stauts,
    ma.street_number               as street_number,
    msn.street_direction_code      as street_direction,
    msn.street_name                as street_name,
    msn.street_type_suffix_code    as street_suffix,
    msn.post_direction_suffix_code as post_direction,
    masu.sudtype,
    masu.subunit_identifier        as sudnum,
    ma.city,
    ma.zip,
    lc.location_class,
    ma.address_type,
    ma.street_id,
    ma.gov_jur_id,
    ma.notes
from      eng.address_location             al
     join eng.mast_address                 ma    on al.street_address_id = ma.street_address_id
     join eng.mast_address_location_status als   on al.location_id = als.location_id
     join eng.mast_address_status          mas   on ma.street_address_id = mas.street_address_id
     join eng.mast_street                  ms    on ma.street_id = ms.street_id
     join eng.mast_street_names            msn   on ms.street_id = msn.street_id and msn.street_name_type = 'STREET'
left join eng.mast_address_subunits        masu  on al.subunit_id = masu.subunit_id
left join gis.locations_classes            lc    on al.location_id = lc.location_id
left join eng.mast_address_subunit_status  masus on al.subunit_id = masus.subunit_id;
