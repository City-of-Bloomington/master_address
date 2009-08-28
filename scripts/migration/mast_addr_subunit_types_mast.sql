create table temp (
	id number not null primary key,
	sudtype varchar2(20) not null,
	description varchar2(40) not null,
	unique (sudtype)
);

create sequence subunit_type_id_s nocache;

insert into temp (id, sudtype, description)
select subunit_type_id_s.nextval, sudtype, description
from mast_addr_subunit_types_mast;

-- Manual changes
-- Drop table mast_addr_subunit_types_mast
-- rename temp to mast_addr_subunit_types_mast


create trigger subunit_type_trigger
before insert on mast_addr_subunit_types_mast
for each row
begin
select subunit_type_id_s.nextval into :new.id from dual;
end;
/

