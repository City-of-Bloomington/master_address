create table temp (
	id number not null primary key,
	status_code number not null enable,
	effective_start_date date not null enable,
	location_id number not null enable,
	effective_end_date date,
	unique (status_code,effective_start_date,location_id),
	foreign key (status_code) references mast_address_status_lookup (status_code)
);

create sequence location_status_id_seq nocache;

insert into temp (id, status_code, effective_start_date, location_id, effective_end_date)
select location_status_id_seq.nextval, status_code, effective_start_date, location_id, effective_end_date
from mast_address_location_status;

-- Manual changes
-- Drop table mast_address_location_status
-- renamte temp to mast_address_location_status

create trigger location_status_trigger
before insert on mast_address_location_status
for each row
begin
select location_status_id_seq.nextval into :new.id from dual;
end;
/

