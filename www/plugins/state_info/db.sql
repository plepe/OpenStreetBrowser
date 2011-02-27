-- status
drop table if exists osm_status;
create table osm_status (
  now		timestamp with time zone,
  last_change	timestamp without time zone
);

insert
  into osm_status
  values (
    now(),
    (select tstamp from nodes where id=(select max(id) from nodes))
  );
