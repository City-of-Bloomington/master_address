create table zipcodes (
	zip number not null primary key,
	city varchar2(20) not null
);

-- Some data cleanup to check
update mast_address set city=lower(city) where city='BLOOMINGTON';
update mast_address set city=initcap(city) where city='bloomington';
update mast_address set zip=null where zip='IN';
update mast_address set zip=47401 where zip=47041;
update mast_address set city='Bloomington' where zip=47403;
update mast_address set city='Springville' where zip=47462;
update mast_address set zip=47404 where zip=48408;
update mast_address set city='Ellettsville' where zip=47429;


insert into zipcodes (zip,city)
select distinct zip,city from mast_address where zip is not null order by city;

alter table mast_address add newzip number;
update mast_address set newzip=zip;
alter table mast_address drop column zip;
alter table mast_address change newzip zip number;


-- Must run as root
grant references on master_address.zipcodes to public;
alter table eng.mast_address add foreign key (zip) references master_address.zipcodes(zip);