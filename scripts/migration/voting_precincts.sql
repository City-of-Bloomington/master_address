create table temp (
	id number not null primary key,
	precinct varchar2(6) not null,
	precinct_name varchar2(20),
	active char(1) not null,
	unique (precinct)
);

create sequence precinct_id_seq nocache;

insert into temp (id, precinct, precinct_name, active)
select precinct_id_seq.nextval, precinct, precinct_name, active
from voting_precincts;

drop table voting_precincts cascade constraints purge;
rename temp to voting_precincts;

create trigger precinct_id_trigger
before insert on voting_precincts
for each row
begin
select precinct_id_seq.nextval into :new.id from dual;
end;
/

