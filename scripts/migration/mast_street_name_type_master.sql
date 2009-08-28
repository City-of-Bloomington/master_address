-- Street Name Types
create table temp (
	id number not null primary key,
	street_name_type varchar2(20) not null,
	description varchar2(240) not null,
	unique (street_name_type)
);
create sequence street_name_type_id_s nocache;

insert into temp (id, street_name_type, description)
select street_name_type_id_s.nextval, street_name_type, description
from mast_street_name_type_master;

-- Manual Changes
-- Drop foreign keys from mast_street_names
-- Drop table mast_street_name_type_master
-- rename temp to mast_street_name_type_master

alter table mast_street_names add foreign key (street_name_type) references mast_street_name_type_master(street_name_type);



create trigger street_name_type_trigger
before insert on mast_street_name_type_master
for each row
begin
select street_name_type_id_s.nextval into :new.id from dual;
end;
/
