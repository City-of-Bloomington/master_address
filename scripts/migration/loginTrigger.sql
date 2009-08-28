-- This has to be run as root
create or replace trigger master_address.session_setup_trigger
after logon on database
begin
  if (user = 'MASTER_ADDRESS') then
    execute immediate 'alter session set nls_date_format="YYYY-MM-DD HH24:MI:SS"';
    execute immediate 'alter session set current_schema=MASTER_ADDRESS';
    execute immediate 'alter session set nls_comp=linguistic';
    execute immediate 'alter session set nls_sort=binary_ci';
  end if;
end session_setup_trigger;