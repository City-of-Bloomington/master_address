create or replace view eng.parks_addr_view as
select
    ma.street_address_id,
    al.location_id,
    ma.street_number||' '||s.street_direction_code||' '||s.street_name||' '||s.street_type_suffix_code as address,
    ma.latitude,
    ma.longitude,
    ma.state_plane_x_coordinate,
    ma.state_plane_y_coordinate
from eng.mast_address               ma
join eng.address_location           al   on ma.street_address_id = al.street_address_id
join eng.addr_location_types_master altm on al.location_type_id = altm.location_type_id
join eng.mast_street_names          s    on ma.street_id = s.street_id
where altm.location_type_id = 'GOVERNMENT BLOOMINGTON PARKS';

