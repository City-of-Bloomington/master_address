-- This is in the GIS schema
create table temp (
	id number not null primary key,
	ordinance_number varchar2(12) not null,
	township_id number,
	name varchar2(40),
	passed_date date,
	effective_start_date date,
	annexation_type number,
	acres number(6,2),
	square_miles number(4,2),
	estimate_population number,
	dwelling_units number,
	unique (ordinance_number)
);

create sequence annexations_id_seq nocache;

alter table annexations modify effective_start_date date;

insert into temp (id, ordinance_number, township_id, name, passed_date, effective_start_date, annexation_type,
acres, square_miles, estimate_population, dwelling_units)
select annexations_id_seq.nextval, ordinance_number, township_id, name, passed_date, effective_start_date, annexation_type,
acres, square_miles, estimate_population, dwelling_units
from annexations;

drop table annexations cascade constraints purge;
rename temp to annexations;

create trigger annexations_trigger
before insert on annexations
for each row
begin
select annexations_id_seq.nextval into :new.id from dual;
end;
/

