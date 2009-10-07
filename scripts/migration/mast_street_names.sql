-- Street Names
create table temp (
	id number not null primary key,
	street_id number not null,
	street_name varchar2(60) not null,
	street_type_suffix_code varchar2(8),
	street_name_type varchar2(20),
	effective_start_date date default sysdate,
	effective_end_date date,
	notes varchar2(240),
	street_direction_code char(2),
	post_direction_suffix_code char(2),
	unique (street_id,street_name),
	foreign key (street_id) references mast_street(street_id),
	foreign key (street_type_suffix_code) references mast_street_type_suffix_master(suffix_code),
	foreign key (street_name_type) references mast_street_name_type_master(street_name_type)
);
create sequence street_names_id_s nocache;

insert into temp (id, street_id, street_name, street_type_suffix_code, street_name_type,
	effective_start_date, effective_end_date, notes, street_direction_code, post_direction_suffix_code)
select street_names_id_s.nextval, street_id, street_name, street_type_suffix_code, street_name_type,
	effective_start_date, effective_end_date, notes, street_direction_code, post_direction_suffix_code
from mast_street_names;

drop table mast_street_names;
rename temp to mast_street_names;

create trigger street_names_trigger
before insert on mast_street_names
for each row
begin
select street_names_id_s.nextval into :new.id from dual;
end;
/
