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
SELECT l.location_id, RTRIM(n.name) name, RTRIM(n.address) address, RTRIM(n.city) city,
       RTRIM(n.state) state, RTRIM(n.zip) zip, RTRIM(n.phone_work) phone_work,
	   RTRIM(n.phone_home) phone_home, RTRIM(n.notes) notes
from ce.name@earthgis.world n
left join ce.regid_name@earthgis.world rn on n.name_num=rn.name_num
left join ce.registr@earthgis.world r on rn.id=r.id
left join ce.address@earthgis.world a on r.id=a.registr_id
left join locations_all_v l on (
	a.street_num=l.street_number
	and trim(a.street_dir)=trim(l.street_direction_code)
	and a.street_name=l.street_name
	and a.street_type=l.street_type_suffix_code
	and nvl(trim(a.post_dir),'null')=nvl(trim(l.post_direction_suffix_code),'null')
	and nvl(a.sud_type,'null')=nvl(l.subunit_type,'null')
	and nvl(a.sud_num,'null')=nvl(l.subunit,'null')
)
where l.loc_active_flag='Y';





left join eng.address_location l on
left join eng.mast_address addr on l.street_address_id=addr.street_address_id
left join eng.mast_street street on addr.street_id=street.street_id
left join eng.mast_street_names sn on (street.street_id=sn.street_id and sn.street_name_type='STREET')
left join eng.mast_address_subunits sub on addr.street_address_id=sub.street_address_id




SELECT loc.location_id, RTRIM(n.name) name,
	RTRIM(n.address) address, RTRIM(n.city) city,
	RTRIM(n.state) state, RTRIM(n.zip) zip, RTRIM(n.phone_work) phone_work,
	RTRIM(n.phone_home) phone_home, RTRIM(n.notes) notes
FROM
	ce.name n,
	ce.regid_name rn,
	ce.registr r,
	ce.address a,
	eng.mast_address addr,
	eng.mast_street street,
	eng.mast_street_names names,
	eng.mast_address_subunits suds,
	eng.address_location loc
WHERE n.name_num = rn.name_num
AND r.id = rn.id
and a.registr_id = r.id
AND a.street_name = RTRIM(names.street_name)
AND a.street_num = NVL(addr.street_number, addr.street_address_2)
AND a.street_dir = RTRIM(names.street_direction_code)
AND a.street_type = RTRIM(names.street_type_suffix_code)
AND loc.street_address_id = addr.street_address_id
AND loc.active = 'Y'
AND addr.street_id = street.street_id
AND (street.street_id = names.street_id AND names.street_name_type = 'STREET')
AND loc.subunit_id = suds.subunit_id
AND NVL(a.sud_type,'XXX') = NVL(suds.sudtype,'XXX')
AND NVL(a.sud_num,'XXX') = NVL(suds.STREET_SUBUNIT_IDENTIFIER,'XXX')
;




SELECT RTRIM(n.name) name,
	RTRIM(n.address) address, RTRIM(n.city) city,
	RTRIM(n.state) state, RTRIM(n.zip) zip, RTRIM(n.phone_work) phone_work,
	RTRIM(n.phone_home) phone_home, RTRIM(n.notes) notes
FROM
	ce.name n
	ce.regid_name rn,
	ce.registr r,
	ce.address a,
	eng.mast_address addr,
	eng.mast_street streets,
	eng.mast_street_names names
WHERE n.name_num = rn.name_num
AND r.id = rn.id
and a.registr_id = r.id
AND a.street_name = RTRIM(names.street_name)
AND a.street_num = NVL(addr.street_number, addr.street_address_2)
AND a.street_dir = RTRIM(names.street_direction_code)
AND a.street_type = RTRIM(names.street_type_suffix_code)
;
