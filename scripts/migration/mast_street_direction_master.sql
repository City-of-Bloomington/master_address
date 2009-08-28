create table temp (
	id number not null primary key,
	direction_code char(2) not null,
	description varchar2(12) not null,
	unique (direction_code)
);

create sequence direction_id_seq nocache;

insert into temp (id, direction_code, description)
select direction_id_seq.nextval, direction_code, description
from mast_street_direction_master;

-- Manual changes
-- Delete the foreign keys from mast_street
-- Drop mast_street_direction_master
-- rename temp to mast_street_direction_master

alter table mast_street add foreign key (street_direction_code) references mast_street_direction_master(direction_code);
alter table mast_street add foreign key (post_direction_suffix_code) references mast_street_direction_master(direction_code);

create trigger direction_id_trigger
before insert on mast_street_direction_master
for each row
begin
select direction_id_seq.nextval into :new.id from dual;
end;
/
