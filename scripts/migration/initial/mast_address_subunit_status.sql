create table temp (
	id number not null primary key,
	subunit_id number not null,
	street_address_id number not null,
	status_code number not null,
	start_date date default sysdate,
	end_date date,
	unique (subunit_id,start_date),
	foreign key (status_code) references mast_address_status_lookup (status_code),
	foreign key (street_address_id) references mast_address (street_address_id),
	foreign key (subunit_id) references mast_address_subunits (subunit_id)
);

create sequence subunit_status_id_seq;

-- Warning there are rows where the status_code is null. These need to be cleaned up
update mast_address_subunit_status set status_code=1 where status_code is null;


insert into temp (id,subunit_id,street_address_id,status_code,start_date,end_date)
select subunit_status_id_seq.nextval,subunit_id,street_address_id,status_code,start_date,end_date
from mast_address_subunit_status;

drop table mast_address_subunit_status cascade constraints purge;
rename temp to mast_address_subunit_status;

create trigger subunit_status_trigger
before insert on mast_address_subunit_status
for each row
begin
select subunit_status_id_seq.nextval into :new.id from dual;
end;
/

