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