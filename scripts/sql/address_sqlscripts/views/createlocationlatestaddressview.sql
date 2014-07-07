--latest location address with subunits
create or replace view eng.location_latest_address_v as
select distinct
    al.location_id,
    ma.street_id,
    rtrim(msn.street_direction_code)   || ' '
        || msn.street_name             || ' '
        || msn.street_type_suffix_code || ' '
        || rtrim(msn.post_direction_suffix_code)   as Street,
    ma.street_number                               as Street_Number,
    rtrim(msn.street_direction_code)               as Direction,
    msn.street_name                                as Street_Name,
    msn.street_type_suffix_code                    as Suffix,
    rtrim(msn.post_direction_suffix_code)          as Post_Dir,
    masu.sudtype || ' ' || masu.subunit_identifier as Subunit,
    ma.street_number                             || ' '
        || rtrim(msn.street_direction_code)      || ' '
        || msn.street_name                       || ' '
        || msn.street_type_suffix_code           || ' '
        || rtrim(msn.post_direction_suffix_code) || ' '
        || masu.sudtype                          || ' '
        || masu.subunit_identifier as Address,
    ma.city || ', IN ' || ma.zip   as City_State_Zip,
    ma.state_plane_x_coordinate    as x_coordinate,
    ma.state_plane_y_coordinate    as y_coordinate,
    ma.latitude,
    ma.longitude,
    mals.description as Status,
    ma.address_type,
    al.mailable_flag as Mailable,
    al.livable_flag as Livable

from      eng.address_location            al
     join eng.mast_address                ma    on al.street_address_id = ma.street_address_id
     join eng.mast_street                 ms    on ma.street_id = ms.street_id
     join eng.mast_street_names           msn   on ms.street_id = msn.street_id and msn.street_name_type='STREET'
     join eng.mast_address_status         mas   on ma.street_address_id = mas.street_address_id and mas.end_date is null
left join eng.mast_address_subunits       masu  on al.subunit_id = masu.subunit_id
left join eng.mast_address_subunit_status masus on masu.subunit_id = masus.subunit_id
     join master_address.mast_address_latest_status mals on al.street_address_id = mals.street_address_id

where al.active = 'Y'
order by Street_Name, Suffix, Direction, Post_Dir, Street_Number, Subunit;

grant select on eng.location_latest_address_v to public

--testers addresses - remove when query is working.
 --and ma.street_id = 1 -- e 10th st
-- and ma.street_address_id = 18524 -- 111 e 10th st  4 units
 --and ma.street_address_id = 4572 -- 113 e 10th st no unit
-- and al.location_id = 41290 -- 101 W 2nd Street - location with 2 addresses

--testing order
--order by location_id
