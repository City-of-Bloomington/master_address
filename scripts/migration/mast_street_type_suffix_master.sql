-- Street Types
create table temp (
	id number not null primary key,
	suffix_code varchar2(8) not null,
	description varchar2(240) not null,
	unique (suffix_code)
);

create sequence suffix_id_seq nocache;

insert into temp (id, suffix_code, description)
select suffix_id_seq.nextval, suffix_code, description
from mast_street_type_suffix_master;

-- Manual Changes
-- Drop foreign keys from mast_street_names
-- Drop table mast_street_type_suffix_master
-- rename temp to mast_street_type_suffix_master

alter table mast_street_names add foreign key (street_type_suffix_code) references mast_street_type_suffix_master(suffix_code);

create trigger suffix_id_trigger
before insert on mast_street_type_suffix_master
for each row
begin
select suffix_id_seq.nextval into :new.id from dual;
end;
/
