drop table if exists planet_osm_place;
create table planet_osm_place (
  id_place_node	int4	,
  id_place_poly	int4	,
  place		text	not null,
  place_level	int	, -- for sorting
  name		text	,
  name_en	text	,
  population	int4	,
  pop_cat	text	
);
SELECT AddGeometryColumn('planet_osm_place', 'label', 900913, 'POINT', 2);
SELECT AddGeometryColumn('planet_osm_place', 'area', 900913, 'GEOMETRY', 2);
SELECT AddGeometryColumn('planet_osm_place', 'guess_area', 900913, 'POLYGON', 2);

insert into planet_osm_place 
  select poi.osm_id, planet_osm_boundaries.rel_id, poi.place, 
    (CASE WHEN place='continent' THEN 0
      WHEN place='country' THEN 1
      WHEN place='state' THEN 2
      WHEN place='city' THEN 3
      WHEN place='town' THEN 4
      WHEN place='region' THEN 5
      WHEN place='county' THEN 6
      WHEN place='suburb' THEN 7
      WHEN place='village' THEN 8
      WHEN place='hamlet' THEN 9
      WHEN place='locality' THEN 10
      WHEN place='island' THEN 11
      ELSE null END) as place_level,
    poi.name, name_en.v,
    cast(pop.v as int4) as population,
    (CASE WHEN cast(pop.v as int4)>1000000 THEN 'L' 
      WHEN cast(pop.v as int4)>500000 THEN 'M' 
      ELSE 'S' END),
    poi.way,
    planet_osm_boundaries.way,
    (CASE WHEN planet_osm_boundaries.poly is not null THEN planet_osm_boundaries.poly ELSE Buffer(poi.way, (CASE WHEN place='city' THEN 20000 WHEN place='town' THEN 5000 WHEN place='village' THEN 2000 WHEN place='hamlet' THEN 1000 END)) END)
  from planet_osm_point poi 
    left join node_tags pop on poi.osm_id=pop.node_id and 
      pop.k='population' and pop.v similar to '^[0-9]+$'
    left join node_tags name_en on poi.osm_id=name_en.node_id and 
      name_en.k='name:en' and poi.name!=name_en.v
    left join planet_osm_boundaries on poi.osm_id=planet_osm_boundaries.place_id
  where place is not null
  order by place_level ASC, population DESC;

create index planet_osm_place_label on planet_osm_place using gist(label);
create index planet_osm_place_guess_area on planet_osm_place using gist(guess_area);
create index planet_osm_place_area on planet_osm_place using gist(area);
create index planet_osm_place_name on planet_osm_place(name);
create index planet_osm_place_node_id on planet_osm_place(id_place_node);


--alter table planet_osm_point drop column population;
--alter table planet_osm_point add column population int4 null;
--update planet_osm_point set population=cast(node_tags.v as int4) from node_tags where planet_osm_point.place is not null and node_tags.node_id=planet_osm_point.osm_id and node_tags.k='population' and node_tags.v similar to '^[0-9]*$';
--update planet_osm_point set population='0' where population is null and place is not null;
--
--alter table planet_osm_point add column place_level int4 null;
--update planet_osm_point set place_level=(CASE WHEN place='continent' THEN 0
--WHEN place='country' THEN 1
--WHEN place='state' THEN 2
--WHEN place='city' THEN 3
--WHEN place='town' THEN 4
--WHEN place='region' THEN 5
--WHEN place='county' THEN 6
--WHEN place='suburb' THEN 7
--WHEN place='village' THEN 8
--WHEN place='hamlet' THEN 9
--WHEN place='locality' THEN 10
--WHEN place='island' THEN 11
--ELSE null END) where place is not null;
--
--alter table planet_osm_point add column name_en text;
--update planet_osm_point set name_en=node_tags.v from node_tags where planet_osm_point.osm_id=node_tags.node_id and node_tags.k='name:en' and planet_osm_point.name!=node_tags.v;
--
--create index planet_osm_point_place_level on planet_osm_point(place_level);
--create index planet_osm_point_population on planet_osm_point(population);

delete from search where amenity_type='place';
insert into search select (select orig_place.v from node_tags orig_place where orig_place.k='name' and orig_place.node_id=id) as  name, name.v as loc_name, (CASE WHEN name.k='name' THEN null ELSE substr(name.k, 6) END) as language, 'node' as type, nodes.id, 'place' as amenity_type, place.v as amenity_val from nodes join node_tags place on nodes.id=place.node_id join node_tags name on nodes.id=name.node_id where place.k='place' and (name.k='name' or name.k like 'name:%');

