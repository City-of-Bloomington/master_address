insert into RENTAL.NAME select * from CE.NAME;
insert into RENTAL.PROP_STATUS select * from CE.PROP_STATUS;
insert into RENTAL.PULL_REAS select * from CE.PULL_REAS;
insert into RENTAL.ZONING_2007 select * from CE.ZONING_2007;
insert into RENTAL.ZONING select * from CE.ZONING;
insert into RENTAL.REGISTR select * from CE.REGISTR;
insert into RENTAL.address2 (street_num, street_dir, street_name, street_type, post_dir, sud_type, sud_num, invalid_addr, registr_id, id)
    select * from CE.address2;
insert into RENTAL.c_types select * from CE.c_types;
insert into RENTAL.email_logs select * from CE.email_logs;
insert into RENTAL.inspection_types select * from CE.inspection_types;
insert into RENTAL.inspections select * from CE.inspections;
insert into RENTAL.inspectors select * from CE.inspectors;
insert into RENTAL.owner_phones select * from CE.owner_phones;
insert into RENTAL.reg_bills select * from CE.reg_bills;
insert into RENTAL.reg_paid select * from CE.reg_paid;
insert into RENTAL.regid_name select * from CE.regid_name;
insert into RENTAL.rental_authorized select * from CE.rental_authorized;
insert into RENTAL.rental_image select * from CE.rental_image;
insert into RENTAL.rental_pull_hist select * from CE.rental_pull_hist;
insert into RENTAL.rental_structures select * from CE.rental_structures;
insert into RENTAL.rental_units select * from CE.rental_units;
insert into RENTAL.rental_updates select * from CE.rental_updates;

create table rental.temp_VARIANCES as select VARIANCE_DATE, to_lob(VARIANCE) VARIANCE, ID, VID from ce.VARIANCES;
insert into RENTAL.variances select * from rental.temp_variances;
drop table rental.temp_VARIANCES;
