-- Converts old data into the new Mixed case
update mast_address set
	address_type=initcap(address_type),
	street_address_2=initcap(street_address_2),
	city=initcap(city);

update annexations set name=initcap(name);

update buildings set building_name=initcap(building_name);

update mast_addr_assignment_contact set
	last_name=initcap(last_name),
	first_name=initcap(first_name),
	contact_type=initcap(contact_type),
	agency=initcap(agency);

update governmental_jurisdiction_mast set description=initcap(description);

update plat_master set name=initcap(name);

update mast_street_names set street_name=initcap(street_name);

update subdivision_names set name=initcap(name),phase=initcap(phase);

update towns_master set description=initcap(description);

update township_master set name=initcap(name);