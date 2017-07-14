insert into address.people (id, firstname, lastname, email, username, authenticationMethod, role) (
    select p.id, p.firstname, p.lastname, p.email, u.username, u.authenticationMethod, r.name
    from master_address.people     p
    left join master_address.users      u  on p.id=u.person_id
    left join master_address.user_roles ur on u.id=ur.user_id
    left join master_address.roles      r  on ur.role_id=r.id
);

insert into address.quarterSections (code) (
    select quarter_section from eng.quarter_section_master
);

insert into address.zipCodes (zip, city, state) (
    select zip, city, 'IN' from master_address.zipcodes
);

insert into address.trashDays (name) (
    select trash_pickup_day from eng.trash_pickup_master
);

insert into address.recycleWeeks (code) (
    select recycle_week from eng.trash_recycle_week_master
);

insert into address.towns         select * from eng.towns_master;
insert into address.townships     select * from eng.township_master;
insert into address.jurisdictions select * from eng.governmental_jurisdiction_mast;

insert into address.directions (id, name, code) (
    select id, description, direction_code from eng.mast_street_direction_master
);

insert into address.addressStatuses select * from eng.mast_address_status_lookup;
insert into address.contactStatuses select * from eng.contactstatus;
insert into address.streetStatuses  select * from eng.mast_street_status_lookup;
insert into address.streetTypes     select * from eng.mast_street_type_suffix_master;
insert into address.subunitTypes    select * from eng.mast_addr_subunit_types_mast;
insert into address.streetNameTypes select * from eng.mast_street_name_type_master;

insert into address.locationTypes (code, name, description) (
    select location_code, location_type_id, description from eng.addr_location_types_master
);
insert into address.locationPurposes select * from eng.addr_location_purpose_mast;

insert into address.plats (id, platType, name, cabinet, envelope, notes, township_id, startDate, endDate) (
    select plat_id, plat_type, name, plat_cabinet, envelope, notes, township_id, effective_start_date, effective_end_date
    from eng.plat_master
);

insert into address.subdivisions (id, township_id, name, phase, status) (
    select s.subdivision_id, s.township_id, n.name, n.phase::integer, n.status
    from eng.subdivision_master s
    join eng.subdivision_names  n on s.subdivision_id=n.subdivision_id
);

insert into address.streets select * from eng.mast_street;
insert into address.streetNames (id, direction_id, name, postDirection_id, notes) (
    select n.id, sd.id, n.street_name, pd.id, n.notes
    from eng.mast_street_names   n
    left join address.directions sd on n.street_direction_code=sd.code
    left join address.directions pd on n.post_direction_suffix_code=pd.code
);

insert into address.street_streetNames (street_id, streetName_id, type_id, startDate, endDate) (
    select n.street_id, n.id, t.id, n.effective_start_date, n.effective_end_date
    from      eng.mast_street_names   n
    left join address.streetNameTypes t on n.street_name_type=t.name
);

-- Clean out some bad data
update eng.mast_address set zip=47404, zipplus4=null where zipplus4='47404';
update eng.mast_address set zip=47403, zipplus4=null where zipplus4='47403';
update eng.mast_address set zipplus4=null where zipplus4='39800';

insert into address.addresses (
    id,
    streetNumberPrefix,
    streetNumber,
    streetNumberSuffix,
    adddress2,
    addressType,
    street_id,
    jurisdiction_id,
    township_id,
    subdivision_id,
    plat_id,
    section,
    quarterSection,
    plat_lotNumber,
    city,
    state,
    zip,
    zipplus4,
    statePlaneX,
    statePlaneY,
    latitude,
    longitude,
    usng,
    geom
) (select  street_address_id,
        street_number_prefix,
        street_number,
        street_number_suffix,
        street_address_2,
        address_type,
        street_id,
        gov_jur_id,
        township_id,
        subdivision_id,
        plat_id,
        section,
        quarter_section,
        plat_lot_number,
        city,
        state,
        zip,
        zipplus4::smallint,
        state_plane_x_coordinate,
        state_plane_y_coordinate,
        latitude,
        longitude,
        usng_coordinate,
        geom
from eng.mast_address);

insert into address.address_status select * from eng.mast_address_status;

insert into address.subunits (
    select  s.subunit_id, s.street_address_id, t.id, s.subunit_identifier, s.notes,
            s.state_plane_x_coordinate, s.state_plane_y_coordinate, s.latitude, s.longitude,
            s.usng_coordinate, s.geom
    from eng.mast_address_subunits s
    left join address.subunitTypes t on s.sudtype=t.code
);

insert into address.subunit_status (
    select id, subunit_id, status_code, start_date, end_date from eng.mast_address_subunit_status
);

insert into address.locations (
    select  l.location_id, t.id, l.street_address_id, l.subunit_id,
            case when l.mailable_flag='yes' then TRUE
                 when l.mailable_flag='no'  then FALSE
                 else null
            end as mailable,
            case when l.livable_flag='yes' then TRUE
                 when l.livable_flag='no'  then FALSE
                 else null
            end as occupiable,
            case when l.active='Y' then TRUE
                 when l.active='N' then FALSE
                 else null
            end as active
    from eng.address_location l
    left join address.locationTypes t on l.location_type_id=t.name
);


insert into address.location_status (
    select id, location_id, status_code, effective_start_date, effective_end_date from eng.mast_address_location_status
);

insert into address.sanitation (address_id, trashDay_id, recycleWeek_code) (
    select s.street_address_id, t.id, s.recycle_week
    from eng.mast_address_sanitation s
    left join address.trashDays t on s.trash_pickup_day=t.name
);

insert into address.contacts (id, status_id, contactType, lastname, firstname, phone, agency, email, notification, coordination) (
    select  contact_id, status_id, contact_type, last_name, first_name, phone_number, agency, email,
            case when notification='Y' then TRUE
                 else null
            end as notification,
            case when coordination='Y' then TRUE
                 else null
            end as coordination
    from eng.mast_addr_assignment_contact
);

-- Clean out bad data
update eng.mast_address_assignment_hist set contact_id=null where contact_id=0;
insert into address.addressAssignmentLog
            (address_id,      location_id, subunit_id, contact_id, actionDate,  action, notes) (
    select a.street_address_id, h.location_id, h.subunit_id, h.contact_id, h.action_date, h.action, h.notes
    from eng.mast_address_assignment_hist h
    join eng.mast_address a on h.street_address_id=a.street_address_id
);

-- person_id == user_id
-- It just so happens that in our old data,
-- everyone's person_id was the same as their user_id
insert into address.addressChangeLog (address_id, person_id, contact_id, actionDate, action, notes) (
    select street_address_id, user_id, contact_id, action_date, action, notes from master_address.address_change_log
);

insert into address.locationChangeLog select * from eng.mast_address_location_change;

insert into address.streetChangeLog (street_id, person_id, contact_id, actionDate, action, notes) (
    select street_id, user_id, contact_id, action_date, action, notes
    from master_address.street_change_log
);

insert into address.subunitChangeLog (subunit_id, person_id, contact_id, actionDate, action, notes) (
    select subunit_id, user_id, contact_id, action_date, action, notes
    from master_address.subunit_change_log
);
