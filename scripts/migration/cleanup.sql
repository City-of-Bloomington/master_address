update plat_master set plat_cabinet=trim(plat_cabinet);

update mast_street_names set street_direction_code=trim(street_direction_code);
update mast_street_names set post_direction_suffix_code=trim(post_direction_suffix_code);


-- This trigger is not ever going to work as intended.  It's causing problems.
drop trigger address_location_aiu_trg;
