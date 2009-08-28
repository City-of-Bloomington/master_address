-- You will need to run these as root.
-- The ENG user does not have privileges to create views.
create view eng.latest_subunit_status as
select z.*,s.status_code,l.description from eng.mast_address_subunit_status s
left join eng.mast_address_status_lookup l on s.status_code=l.status_code
right join (
	select subunit_id,max(start_date) as start_date
	from eng.mast_address_subunit_status group by subunit_id
) z on (s.subunit_id=z.subunit_id and s.start_date=z.start_date);

create view eng.mast_address_latest_status as
select z.*,s.status_code,l.description from eng.mast_address_status s
left join eng.mast_address_status_lookup l on s.status_code=l.status_code
right join (
	select street_address_id,max(start_date) as start_date
	from eng.mast_address_status group by street_address_id
) z on (s.street_address_id=z.street_address_id and s.start_date=z.start_date);
