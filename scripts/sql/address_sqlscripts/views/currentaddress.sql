select al.location_id, ma.street_address_id, ma.street_number, ma.street_id,
ma.state_plane_x_coordinate, ma.state_plane_y_coordinate
from eng.address_location al, eng.mast_address ma, eng.mast_address_status mas
--, eng.mast_address_location_status ls
where al.street_address_id = ma.street_address_id
and ma.street_address_id = mas.street_address_id
and ma.address_type = 'STREET'
--and ls.status_code = '1'
and mas.status_code = '1'
order by al.location_id, ma.street_number
;
