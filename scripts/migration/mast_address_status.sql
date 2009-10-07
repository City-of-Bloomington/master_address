create table temp (
	id number not null primary key,
	street_address_id number not null,
	status_code number not null,
	start_date date default sysdate,
	end_date date,
	unique (street_address_id,start_date),
	foreign key (status_code) references mast_address_status_lookup (status_code),
	foreign key (street_address_id) references mast_address (street_address_id)
);

create sequence address_status_id_seq nocache;

insert into temp (id,street_address_id,status_code,start_date,end_date)
select address_status_id_seq.nextval,street_address_id,status_code,start_date,end_date
from mast_address_status;

drop table mast_address_status cascade constraints purge;
rename temp to mast_address_status;


create trigger address_status_trigger
before insert on mast_address_status
for each row
begin
select address_status_id_seq.nextval into :new.id from dual;
end;
/

