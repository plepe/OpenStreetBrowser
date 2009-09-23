-- table point gets a flag, whether it is part of a station-rel
alter table planet_osm_poipoly add column part_of_station int;
update planet_osm_poipoly set part_of_station=1 from relation_members, planet_osm_rels where planet_osm_poipoly.osm_id=relation_members.member_id and relation_members.member_type='1' and planet_osm_rels.id=relation_members.relation_id and planet_osm_rels.type='station' and relation_members.member_role!='nearby';

-- update importance in point where missing or wrong
update planet_osm_poipoly set importance=network where importance is null and network in ('local', 'suburban', 'urban', 'regional', 'national', 'international');
update planet_osm_poipoly set importance='regional' where importance='region';
update planet_osm_poipoly set importance='local' where highway='bus_stop' and (importance is null or not importance in ('local', 'urban', 'regional', 'national', 'international'));
update planet_osm_poipoly set importance='local' where railway='tram_stop' and (importance is null or not importance in ('local', 'urban', 'regional', 'national', 'international'));
update planet_osm_poipoly set importance='suburban' where railway='halt' and not (importance is null or importance in ('local', 'urban', 'regional', 'national', 'international'));
update planet_osm_poipoly set importance='suburban', railway='subway_station' from planet_osm_line l join planet_osm_ways w on l.osm_id=w.id where planet_osm_poipoly.osm_id=any(w.nodes) and planet_osm_poipoly.railway='station' and l.railway='subway' and (planet_osm_poipoly.importance is null or not planet_osm_poipoly.importance in ('local', 'urban', 'regional', 'national', 'international'));
update planet_osm_poipoly set importance='urban' where railway='station' and (importance is null or not importance in ('local', 'urban', 'regional', 'national', 'international'));
update planet_osm_poipoly set importance='urban' where amenity='bus_station' and (importance is null or not importance in ('local', 'urban', 'regional', 'national', 'international'));
update planet_osm_poipoly set importance='national' where aeroway='aerodrome' and (importance is null or not importance in ('local', 'urban', 'regional', 'national', 'international'));
update planet_osm_poipoly set importance='suburban' where aerialway='station' and (importance is null or not importance in ('local', 'urban', 'regional', 'national', 'international'));
update planet_osm_poipoly set importance='urban' where amenity='ferry_terminal' and (importance is null or not importance in ('local', 'urban', 'regional', 'national', 'international'));

-- in which direction stations are being used?
drop table if exists planet_osm_stops;
create table planet_osm_stops (
  osm_id	int4	not null,
  id_type	varchar(4) not null,
  full_id	varchar(32) not null,
  type		text	not null,
  importance	text	null,
  angle_p	int	null,
  angle_n	int	null,
  direction	int	not null,
  primary key(osm_id)
);
SELECT AddGeometryColumn('planet_osm_stops', 'way', 900913, 'POINT', 2);

insert into planet_osm_stops
(select osm_id, id_type, full_id, type, importance,
  round(ST_Azimuth((CASE 
      WHEN pos-0.001/len<0 THEN line_interpolate_point(next_way, pos)
      ELSE line_interpolate_point(next_way, pos-0.001/len)
    END), line_interpolate_point(next_way, pos))/(2*pi()/72)) as angle_p,
  round(ST_Azimuth(line_interpolate_point(next_way, pos),
    (CASE 
      WHEN pos+0.001/len>=1 THEN line_interpolate_point(next_way, pos)
      ELSE line_interpolate_point(next_way, pos+0.001/len)
  END))/(2*pi()/72)) as angle_n,
  bit_or((CASE WHEN substr(rm.member_role, 1, 7)='forward' THEN 1 WHEN substr(rm.member_role, 1, 8)='backward' THEN 2 ELSE 3 END)) as direction,
  line_interpolate_point(next_way, pos) as way
from 
(select t.osm_id, t.id_type, t.full_id, type, importance,
  poi_way, next_way, line_locate_point(next_way, poi_way) as pos, length(next_way) as len from (
select poi.osm_id, poi.id_type, poi.full_id,
  (CASE
    WHEN poi.highway='bus_stop' and poi.railway='tram_stop' THEN 'tram_bus_stop'
    WHEN poi.highway in ('bus_stop') THEN poi.highway
    WHEN poi.railway in ('tram_stop', 'station', 'subway_station', 'halt') THEN poi.railway
    WHEN poi.aerialway in ('station') THEN 'aerial_station'
  END) as type, 
  poi.importance,
  poi.way as poi_way,
  (select dst.way
      from planet_osm_line dst
      where
       geometryfromtext('POLYGON((' || xmin(poi.way)-200 || ' ' || ymin(poi.way)-200 || ','
                               || xmax(poi.way)+200 || ' ' || ymin(poi.way)-200 || ','
			       || xmax(poi.way)+200 || ' ' || ymax(poi.way)+200 || ','
			       || xmin(poi.way)-200 || ' ' || ymax(poi.way)+200 || ',' 
			       || xmin(poi.way)-200 || ' ' || ymin(poi.way)-200 || '))',
			        900913)&&dst.way
    order by Distance(poi.way, dst.way) asc limit 1) as next_way
       from planet_osm_poipoly poi where 
  (poi.highway='bus_stop' or
    poi.railway in ('tram_stop', 'station', 'subway_station', 'halt')
    )
      ) as t) as t1 left join relation_members rm on rm.member_id=t1.osm_id and rm.member_type=1 group by t1.osm_id, t1.id_type, t1.full_id, t1.type, t1.pos, t1.len, t1.importance, t1.next_way);

-- stop_to_station finds nearby stops with same name
-- potential BUG: id for point and polygon same in same station
drop table if exists planet_osm_stop_to_station;
create table planet_osm_stop_to_station(
  id varchar(32) not null,
  rel_id int4[],
  stations varchar(32)[],
  name text,
  importance text, 
  primary key(id)
);
insert into planet_osm_stop_to_station select 
  src.full_id,
  array_unique(to_intarray((select station_rel.id from
      planet_osm_rels station_rel 
    join 
      relation_members station_member on 
      station_member.relation_id=station_rel.id and
      station_member.member_role!='nearby'
    where
      station_rel.type='station' and
      src.osm_id=station_member.member_id and
      src.id_type=(array['node','way'])[station_member.member_type]
    limit 1))),
  array_sort(array_unique(to_textarray(dst.full_id))),
  dst.name,
  src.importance
from 
  planet_osm_poipoly as dst,
  planet_osm_poipoly as src
where 
  src.name=dst.name and
  geometryfromtext('POLYGON((' || xmin(src.way)-500 || ' ' || ymin(src.way)-500 || ','
                               || xmax(src.way)+500 || ' ' || ymin(src.way)-500 || ','
			       || xmax(src.way)+500 || ' ' || ymax(src.way)+500 || ','
			       || xmin(src.way)-500 || ' ' || ymax(src.way)+500 || ',' 
			       || xmin(src.way)-500 || ' ' || ymin(src.way)-500 || '))',
			        900913)&&dst.way and
  Distance(src.way, dst.way)<1000 and
  (src.highway='bus_stop' or 
  src.railway in ('tram_stop', 'subway_station', 'station', 'halt') or
    src.amenity='bus_station' or src.aeroway='aerodrome' or
    src.aerialway='station' or src.amenity='ferry_terminal') and
  (dst.highway='bus_stop' or
    dst.railway in ('tram_stop', 'subway_station', 'station', 'halt') or
    dst.amenity='bus_station' or dst.aeroway='aerodrome' or
    dst.aerialway='station' or dst.amenity='ferry_terminal')
group by src.full_id, src.importance, dst.name;

-- delete all stops that are part of a station-rel
delete from planet_osm_stop_to_station using planet_osm_poipoly where planet_osm_stop_to_station.id=planet_osm_poipoly.full_id and part_of_station=1;

-- stations_all combines stops that are close to each other
drop table if exists planet_osm_stations;
create table planet_osm_stations(
  name        text,
  stations    varchar(32)[],
  rel_id      int4,
  coll_id     int4,
  importance  text
);
SELECT AddGeometryColumn('planet_osm_stations', 'way', 900913, 'GEOMETRY', 2);
SELECT AddGeometryColumn('planet_osm_stations', 'center', 900913, 'POINT', 2);
SELECT AddGeometryColumn('planet_osm_stations', 'bbox', 900913, 'LINESTRING', 2);
SELECT AddGeometryColumn('planet_osm_stations', 'top', 900913, 'POINT', 2);
SELECT AddGeometryColumn('planet_osm_stations', 'topline', 900913, 'LINESTRING', 2);
insert into planet_osm_stations
  select station.name,
    stations,
    (array_sort(max(rel_id)))[1],
    cast(substr(stations[1], position('_' in stations[1])+1) as int),
    (array['local','suburban','urban','regional','national','international'])
      [max(CASE
        WHEN station.importance='suburban' THEN 2 
	WHEN station.importance='urban' THEN 3
	WHEN station.importance='regional' THEN 4
	WHEN station.importance='national' THEN 5
	WHEN station.importance='international' THEN 6 ELSE 1 END)],
    ST_Collect(CASE WHEN stops.way is not null THEN stops.way ELSE object.way END)
from planet_osm_stop_to_station station 
  join 
  planet_osm_poipoly as object
     on object.full_id=any(station.stations)
  left join planet_osm_stops stops on object.full_id='node_'||stops.osm_id
group by station.name, stations;

-- we don't need this temporary table anymore
-- drop table planet_osm_stop_to_station;

-- delete all stations with relations and do it again for them
insert into planet_osm_stations select planet_osm_rels.name, to_textarray(planet_osm_poipoly.full_id), planet_osm_rels.id, null, planet_osm_rels.importance, ST_Collect(way)  from planet_osm_rels join relation_members on relation_members.relation_id=planet_osm_rels.id join planet_osm_poipoly on planet_osm_poipoly.osm_id=relation_members.member_id and planet_osm_poipoly.id_type=(array['node', 'way'])[relation_members.member_type] where type='station' group by planet_osm_rels.id, planet_osm_rels.name, planet_osm_rels.importance;

-- create geo objects of stations
update planet_osm_stations set 
  center=ST_Centroid(way),
  bbox=geomfromtext('LINESTRING(' || XMIN(way)||' '||YMIN(way) ||', '|| XMIN(way)||' '||YMAX(way) ||', '|| XMAX(way)||' '||YMAX(way)||', '|| XMAX(way)||' '||YMIN(way)||', '|| XMIN(way)||' '||YMIN(way) ||', '|| XMIN(way)||' '||YMAX(way)|| ')', 900913), 
  top=GeometryFromText('POINT(' || x(centroid(envelope(way))) || ' ' || ymax(envelope(way)) || ')', 900913),
  topline=geomfromtext('LINESTRING(' || XMIN(way)||' '||YMIN(way) ||', '|| XMAX(way)||' '||YMIN(way)|| ')', 900913);

-- feed stations in collection
insert into planet_osm_colls select coll_id, 'station' from planet_osm_stations where rel_id is null and coll_id is not null;
insert into coll_tags select coll_id, 'name', name from planet_osm_stations where rel_id is null and coll_id is not null;
insert into coll_tags select coll_id, 'importance', importance from planet_osm_stations where rel_id is null and coll_id is not null;
insert into coll_tags select coll_id, 'type', 'station' from planet_osm_stations where rel_id is null and coll_id is not null;
insert into coll_members 
  select coll_id, p.osm_id, 
    (CASE WHEN id_type='node' THEN 1
	  WHEN id_type='way'  THEN 2
    END),
    ''
  from planet_osm_stations st
    join planet_osm_poipoly p
      on p.full_id=any(st.stations)
  where rel_id is null and coll_id is not null;

-- feed stations in search
insert into search 
  select name, name, null as language, 
    (CASE 
      WHEN rel_id is not null THEN 'rel'
      WHEN coll_id is not null THEN 'coll'
      ELSE substr(stations[1], 0, position('_' in stations[1]))
    END),
    (CASE
      WHEN rel_id is not null THEN rel_id
      WHEN coll_id is not null THEN coll_id
      ELSE cast(substr(stations[1], position('_' in stations[1])+1) as int)
    END),
    'station', null
  from planet_osm_stations where name is not null;

create index planet_osm_stations_way on planet_osm_stations using gist(way);
create index planet_osm_stations_bbox on planet_osm_stations using gist(bbox);
create index planet_osm_stations_center on planet_osm_stations using gist(center);
create index planet_osm_stations_top on planet_osm_stations using gist(top);
