create or replace view eng.park_address_view as
select
	ma.street_address_id,
	al.location_id,
	altm.location_type_id,
	ma.state_plane_x_coordinate,
	ma.state_plane_y_coordinate,
	ma.latitude,
	ma.longitude
from eng.mast_address               ma
join eng.address_location           al   on ma.street_address_id=al.street_address_id
join eng.addr_location_types_master altm on al.location_type_id = altm.location_type_id
where altm.location_type_id = 'GOVERNMENT BLOOMINGTON PARKS';
