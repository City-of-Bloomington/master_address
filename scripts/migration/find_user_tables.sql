select 'create table ' || owner || '.' || table_name || ' as select * from ' || owner || '.' || table_name || '@proto;' from dba_tables@proto where owner='ENG';
select 'create table ' || owner || '.' || table_name || ' as select * from ' || owner || '.' || table_name || '@proto;' from dba_tables@proto where owner='CE';
select 'create table ' || owner || '.' || table_name || ' as select * from ' || owner || '.' || table_name || '@proto;' from dba_tables@proto where owner='UENG';
select 'create table ' || owner || '.' || table_name || ' as select * from ' || owner || '.' || table_name || '@proto;' from dba_tables@proto where owner='DP';
select 'create table ' || owner || '.' || table_name || ' as select * from ' || owner || '.' || table_name || '@proto;' from dba_tables@proto where owner='E2';
select 'create table ' || owner || '.' || table_name || ' as select * from ' || owner || '.' || table_name || '@proto;' from dba_tables@proto where owner='GIS';
select 'create table ' || owner || '.' || table_name || ' as select * from ' || owner || '.' || table_name || '@proto;' from dba_tables@proto where owner='JAVA';
select 'create table ' || owner || '.' || table_name || ' as select * from ' || owner || '.' || table_name || '@proto;' from dba_tables@proto where owner='UCS';
select 'create table ' || owner || '.' || table_name || ' as select * from ' || owner || '.' || table_name || '@proto;' from dba_tables@proto where owner='UTDB';