create view gis.building_location_classes
as select b.building_id, b.gis_tag, b.status_code, bal.location_id, lc.location_class
from gis.buildings b, gis.building_address_location bal, gis.locations_classes lc
where b.building_id = bal.building_id
and bal.location_id = lc.location_id (+)
;
