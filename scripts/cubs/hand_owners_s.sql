-- Old one
SELECT /*+ ALL_ROWS */ loc.location_id, RTRIM(n.name) name, RTRIM(n.address) address, RTRIM(n.city) city,
       RTRIM(n.state) state, RTRIM(n.zip) zip, RTRIM(n.phone_work) phone_work,
	   RTRIM(n.phone_home) phone_home, RTRIM(n.notes) notes
  FROM eng.address_location@proto.world loc,
       eng.mast_address@proto.world addr,
       eng.mast_street@proto.world street,
       eng.mast_street_names@proto.world names,
       eng.mast_address_subunits@proto.world suds,
       ce.address@proto.world a,
       ce.registr@proto.world r,
       ce.regid_name@proto.world rn,
       ce.name@proto.world n
 WHERE a.registr_id = r.id
   AND a.registr_id = rn.id
   AND r.id = rn.id
   AND n.name_num = rn.name_num
   AND a.street_name = RTRIM(names.street_name)
   AND a.street_num = NVL(addr.street_number, addr.street_address_2)
   AND a.street_dir = RTRIM(names.street_direction_code)
   AND a.street_type = RTRIM(names.street_type_suffix_code)
   AND NVL(a.sud_type,'XXX') = NVL(suds.sudtype,'XXX')
   AND NVL(a.sud_num,'XXX') = NVL(suds.STREET_SUBUNIT_IDENTIFIER,'XXX')
   AND loc.street_address_id = addr.street_address_id
   AND loc.active = 'Y'
   AND addr.street_id = street.street_id
   AND street.street_id = names.street_id
   AND names.street_name_type = 'STREET'
   AND loc.subunit_id = suds.subunit_id(+)
;


-- New one
create materialized view hand_owners_s
tablespace CUBSD
refresh complete on demand start with sysdate+0 next trunc(sysdate+1) + (6.3/24)
disable query rewrite
AS select loc.location_id, RTRIM(n.name) name, RTRIM(n.address) address, RTRIM(n.city) city,
	RTRIM(n.state) state, RTRIM(n.zip) zip, RTRIM(n.phone_work) phone_work,
	RTRIM(n.phone_home) phone_home, RTRIM(n.notes) notes
from ce_name_v n,
	ce_regid_name_v rn,
	ce_registr_v r,
	ce_address_v a,
	locations_all_v loc
where n.name_num=rn.name_num
and rn.id=r.id
and r.id=a.registr_id
and a.street_name = rtrim(loc.street_name)
and a.street_num = nvl(loc.street_number,loc.street_address_2)
and a.street_dir = rtrim(loc.street_direction_code)
and a.street_type = rtrim(loc.street_type_suffix_code)
and nvl(a.sud_type,'xxx') = nvl(loc.subunit_type,'xxx')
and nvl(a.sud_num,'xxx') = nvl(loc.subunit,'xxx')
and loc.loc_active_flag='Y'
and loc.street_name_type='STREET';

