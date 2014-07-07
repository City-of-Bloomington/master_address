create or replace view neighborhood_address_view
as select ma.street_address_id, al.location_id, alp.location_purpose_id, alpm.description
from eng.mast_address ma, eng.address_location al, ENG.ADDR_LOCATION_PURPOSES alp, ENG.ADDR_LOCATION_PURPOSE_MAST alpm
where alp.location_id = al.location_id (+)
and al.street_address_id = ma.street_address_id
and alp.location_purpose_id = alpm.location_purpose_id
and alpm.type = 'NEIGHBORHOOD ASSOCIATION'
--order by ma.street_address_id
;
