-- We're using Governmental Jurisdictions as the only real jurisdictions.
-- To maintain the old jurisdictions table, we're adding the extra values
-- from governmental jurisdictions
insert into addr_jurisdiction_master
select * from governmental_jurisdiction_mast
where gov_jur_id not in (select jurisdiction_id from addr_jurisdiction_master);