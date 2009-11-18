create materialized view hand_agents_s
tablespace "CUBSD"
refresh complete on demand start with sysdate+0 next trunc(sysdate+1) + (6.1/24)
disable query rewrite
as
select
	/*+ all_rows */
	l.location_id,
	a.street_num,
	a.street_dir,
	a.street_name,
	a.street_type,
	a.post_dir,
	a.sud_type,
	a.sud_num,
	r.registered_date,
	n.name,
	n.address,
	n.city,
	n.state,
	n.zip,
	n.phone_work,
	n.phone_home,
	n.notes
from ce_address_v a,
	ce_registr_v r,
	ce_name_v n,
	locations_all_s l
where r.id=a.registr_id
and r.agent=n.name_num
and a.street_name=l.street_name
and a.street_num=l.street_number
and a.street_dir=l.street_direction_code
and a.street_type=l.street_type_suffix_code
and nvl(a.sud_type,'xxx')=nvl(l.sudtype,'xxx')
and nvl(a.sud_num,'xxx')=nvl(l.street_subunit_identifier,'xxx');