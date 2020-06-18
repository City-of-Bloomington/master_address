-- Address activity
select l.location_id,
       c.address_id as entity_id,
       a.address_type,
       c.action_date,
       concat_ws(' ', p.firstname, p.lastname) as person,
       concat_ws(' ',a.street_number_prefix,
                     a.street_number,
                     a.street_number_suffix) as streetNumber,
       n.direction,
       n.name,
       t.code,
       n.post_direction,
       null as subunit,
       a.zip,
       c.action,
      lt.name as location_type,
       c.notes,
       concat_ws(' ', n.direction,
                      n.name,
                      t.code,
                      n.post_direction) as full_street_name,
       concat_ws(' ', a.street_number_prefix,
                      a.street_number,
                      a.street_number_suffix,
                      n.direction,
                      n.name,
                      t.code,
                      n.post_direction) as full_address
from      address_change_log  c
     join addresses           a on  a.id=c.address_id
     join streets             s on  s.id=a.street_id
     join street_designations d on  s.id=d.street_id and d.type_id=1 -- STREET
     join street_names        n on  n.id=d.street_name_id
left join street_types        t on  t.id=n.suffix_code_id
     join people              p on  p.id=c.person_id
     join locations           l on  a.id=l.address_id and l.subunit_id is null
     join location_types     lt on lt.id=l.type_id

where a.jurisdiction_id=1 -- Bloomington
  and action_date between :start_date_1 and :end_date_1

union all

-- Subunit activity
select l.location_id,
       c.subunit_id as entity_id,
       a.address_type,
       c.action_date,
       concat_ws(' ', p.firstname, p.lastname) as person,
       concat_ws(' ',a.street_number_prefix,
                     a.street_number,
                     a.street_number_suffix) as streetNumber,
       n.direction,
       n.name,
       t.code,
       n.post_direction,
       concat_ws(' ', st.code,
                     sub.identifier) as subunit,
       a.zip,
       c.action,
      lt.name as location_type,
       c.notes,
       concat_ws(' ', n.direction,
                      n.name,
                      t.code,
                      n.post_direction) as full_street_name,
       concat_ws(' ', a.street_number_prefix,
                      a.street_number,
                      a.street_number_suffix,
                      n.direction,
                      n.name,
                      t.code,
                      n.post_direction,
                     st.code,
                    sub.identifier) as full_address
from      subunit_change_log  c
     join subunits          sub on sub.id=c.subunit_id
     join subunit_types      st on  st.id=sub.type_id
     join addresses           a on   a.id=sub.address_id
     join streets             s on   s.id=a.street_id
     join street_designations d on   s.id=d.street_id and d.type_id=1 -- STREET
     join street_names        n on   n.id=d.street_name_id
left join street_types        t on   t.id=n.suffix_code_id
     join people              p on   p.id=c.person_id
     join locations           l on sub.id=l.subunit_id
     join location_types     lt on  lt.id=l.type_id

where a.jurisdiction_id=1 -- Bloomington
  and c.action_date between :start_date_2 and :end_date_2
