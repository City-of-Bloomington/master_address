set search_path=address;

create table tmp_addresses (
    location_id int           not null,
     address_id int           not null,
     subunit_id int,
     x          int           not null,
     y          int           not null,
     lat        decimal(10,8) not null,
     lon        decimal(10,8) not null,
     address    varchar(128)  not null,
    foreign key (address_id) references addresses(id),
    foreign key (subunit_id) references  subunits(id)
);

copy tmp_addresses from '/srv/addresses.csv' (format CSV, header true);

update addresses a
set state_plane_x = t.x,
    state_plane_y = t.y,
    latitude      = t.lat,
    longitude     = t.lon
from tmp_addresses t
where a.id=t.address_id;

drop table tmp_addresses;

create table tmp_subunits (
    location_id int           not null,
     address_id int           not null,
     subunit_id int           not null,
     x          int           not null,
     y          int           not null,
     lat        decimal(10,8) not null,
     lon        decimal(10,8) not null,
     address    varchar(128)  not null,
    foreign key (address_id) references addresses(id),
    foreign key (subunit_id) references  subunits(id)
);

copy tmp_subunits from '/srv/subunits.csv' (format CSV, header true);

update subunits s
set state_plane_x = t.x,
    state_plane_y = t.y,
    latitude      = t.lat,
    longitude     = t.lon
from tmp_subunits t
where s.id=t.subunit_id;

drop table tmp_subunits;
