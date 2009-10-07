alter table mast_address add numeric_street_number number;
update mast_address set numeric_street_number=to_number(regexp_substr(street_number,'^\d+'));

create index mast_address_idx1 on mast_address(street_id);
create index mast_address_status_idx1 on mast_address_status(status_code);
