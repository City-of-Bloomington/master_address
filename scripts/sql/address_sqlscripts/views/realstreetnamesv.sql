create or replace view eng.real_street_names_v as
select
    ms.street_id as street_id,
    nvl(msn.street_direction_code,NULL) || ' '
        || msn.street_name              || ' '
        || msn.street_type_suffix_code  || ' '
        || msn.post_direction_suffix_code as street_name
from eng.mast_street       ms
join eng.mast_street_names msn on ms.street_id = msn.street_id and msn.street_name_type = 'STREET';

