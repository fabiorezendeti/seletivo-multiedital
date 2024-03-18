CREATE USER ingresso WITH PASSWORD 'ingresso';
CREATE SCHEMA IF NOT EXISTS core;
GRANT usage ON SCHEMA core to ingresso;
grant select, insert, update, delete on all tables in schema  core to ingresso;
grant all on all sequences in schema  core to ingresso;
alter default privileges in schema core grant select,insert,update,delete on tables to ingresso;
alter default privileges in schema core grant all on sequences to ingresso;

CREATE ROLE csi_ro WITH 
	NOSUPERUSER
	NOCREATEDB
	NOCREATEROLE
	INHERIT
	NOLOGIN
	NOREPLICATION
	NOBYPASSRLS
	CONNECTION LIMIT -1;

CREATE ROLE csi_rw WITH 
	NOSUPERUSER
	NOCREATEDB
	NOCREATEROLE
	INHERIT
	NOLOGIN
	NOREPLICATION
	NOBYPASSRLS
	CONNECTION LIMIT -1;	

do $do$
declare
	sch text;
begin
		for sch in select nspname from pg_catalog.pg_namespace where nspname like 'notice_%'
		loop 
			raise notice '%', sch;
			execute format($$ grant select, insert, update, delete on all tables in schema  %I to ingresso $$, sch);
			execute format($$ grant all on all sequences in schema  %I to ingresso $$, sch);		
			execute format($$ grant usage on schema  %I to ingresso $$, sch);					
		end loop;
end; 
$do$;