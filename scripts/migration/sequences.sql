-- These are in ENG
create sequence address_status_code_seq start with 6 nocache;

create trigger address_status_code_trigger
before insert on mast_address_status_lookup
for each row
begin
select address_status_code_seq.nextval into :new.status_code from dual;
end;
/

create sequence street_status_code_seq start with 5 nocache;

create trigger street_status_code_trigger
before insert on mast_street_status_lookup
for each row
begin
select street_status_code_seq.nextval into :new.status_code from dual;
end;
/


create trigger subunit_id_trigger
before insert on mast_address_subunits
for each row
begin
select subunit_id_s.nextval into :new.subunit_id from dual;
end;
/

-- These are in GIS
create sequence buildings_status_code_seq start with 6 nocache;

create trigger buildings_status_code_trigger
before insert on buildings_status_lookup
for each row
begin
select buildings_status_code_seq.nextval into :new.status_code from dual;
end;
/
