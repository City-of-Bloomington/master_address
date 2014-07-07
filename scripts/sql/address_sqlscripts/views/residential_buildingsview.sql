create or replace view residential_buildings as
	select b.building_id, b.gis_tag, bal.location_id, lc.location_class
		from GIS.BUILDINGS b, GIS.BUILDING_ADDRESS_LOCATION bal, GIS.LOCATIONS_CLASSES lc
			where b.building_id = bal.building_id
			and bal.location_id = lc.location_id (+)
			and lc.location_class in ('MULTI-FAMILY RESIDENTIAL', 'SINGLE-FAMILY RESIDENTIAL');



/*
create or replace view residential_buildings as
	select bal.location_id, bal.building_id
		from GIS.BUILDING_ADDRESS_LOCATION bal, GIS.LOCATIONS_CLASSES lc
			where bal.location_id = lc.location_id
			and lc.location_class in ('MULTI-FAMILY RESIDENTIAL', 'SINGLE-FAMILY RESIDENTIAL');
*/
