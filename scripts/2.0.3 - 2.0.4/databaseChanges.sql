CREATE OR REPLACE VIEW latest_location_status AS
select distinct on (location_id) location_id, start_date, status
from location_status
order by location_id, start_date desc;

CREATE OR REPLACE VIEW latest_subunit_status AS
select distinct on (subunit_id) subunit_id, start_date, status
from subunit_status
order by subunit_id, start_date desc;

CREATE OR REPLACE VIEW latest_address_status AS
select distinct on (address_id) address_id, start_date, status
from address_status
order by address_id, start_date desc;

alter table  address_status drop end_date;
alter table  subunit_status drop end_date;
alter table location_status drop end_date;

delete from street_types where code='BYP';
