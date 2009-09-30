update plat_master set plat_cabinet=trim(plat_cabinet);

update mast_street_names set street_direction_code=trim(street_direction_code);
update mast_street_names set post_direction_suffix_code=trim(post_direction_suffix_code);

create index mast_address_idx1 on mast_address(street_id);
create index mast_address_status_idx1 on mast_address_status(status_code);