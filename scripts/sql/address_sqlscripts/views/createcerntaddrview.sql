-- This is the view used in gis to shade, plot, query building footprints linked to
-- any registered rental addresses (ie. They could have changed to owner occupied, vacant or commerical)

create or replace view ce.rnt_addr as

select
	distinct
	b.gis_tag as tag_num,
	al.location_id,
	ma.street_address_id,
	ra.registr_id,
	r.property_status,
	ma.street_number,
	msn.street_direction_code as direction,
	msn.street_name,
	msn.street_type_suffix_code as suffix,
	msn.post_direction_suffix_code as postdir
from eng.mast_address ma,
	eng.address_location al,
	eng.mast_address_location_status als,
	gis.building_address_location bal,
	gis.buildings b,
	eng.mast_street_names msn,
	eng.mast_address_status mas,
	ce.address2 ra, ce.registr r
where b.building_id = bal.building_id
and bal.location_id = al.location_id
and al.street_address_id = ma.street_address_id
and al.location_id = als.location_id
and ma.street_id = msn.street_id
and ma.street_address_id = mas.street_address_id
and msn.street_name_type = 'STREET'
and al.subunit_id is null
and ma.street_number (+) = ra.street_num
and (TRIM(msn.street_direction_code) = ra.street_dir or ra.street_dir is null)
and UPPER(msn.street_name) = ra.street_name
and UPPER(msn.street_type_suffix_code) = ra.street_type
and (TRIM(msn.post_direction_suffix_code) = ra.post_dir or msn.post_direction_suffix_code is null)
and ra.registr_id = r.id
and mas.status_code = '1'
and als.status_code = '1';
