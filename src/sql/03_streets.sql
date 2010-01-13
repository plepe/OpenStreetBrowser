drop table if exists streets_tmp1;
create table streets_tmp1 (
  osm_id	int4	not null,
  name	text	not null,
  highway_level	int,
  neighbours	int4[]	,
  primary key(osm_id)
);

create index planet_osm_line_name on planet_osm_line(name);

insert into streets_tmp1 
  select distinct 
    root.osm_id, root.name, 
    (CASE 
      WHEN root."network"='international' THEN 4 
      WHEN root."network"='national' THEN 3 
      WHEN root."network"='region' THEN 2 
      WHEN root."network"='urban' THEN 1 
      WHEN root."network"='local' THEN 0 
      WHEN root.highway='motorway' THEN 4 
      WHEN root.highway in ('trunk', 'primary') THEN 3 
      WHEN root.highway in ('secondary', 'tertiary') or root."railway" in ('rail') or root."waterway" in ('river', 'canal') THEN 2 
      WHEN root."waterway" in ('stream') THEN 0 
      ELSE 1 
    END)+(CASE 
      WHEN root."highway" is not null THEN 30 
      WHEN root."railway" is not null THEN 20
      WHEN root."waterway" is not null THEN 10
      ELSE 0 END),
    to_intarray(next.osm_id) as neighbours 
  from planet_osm_line root 
    join planet_osm_line next on root.name=next.name 
  where root.osm_id>0 and next.osm_id>0 and 
    makepolygon(geometryfromtext('LINESTRING(' || xmin(root.way)-200 || ' ' || ymin(root.way)-200 || ',' || xmax(root.way)+200 || ' ' || ymin(root.way)-200 || ',' || xmax(root.way)+200 || ' ' || ymax(root.way)+200 || ',' || xmin(root.way)-200 || ' ' || ymax(root.way)+200 || ',' || xmin(root.way)-200 || ' ' || ymin(root.way)-200 || ')', 900913))&&next.way and
    Distance(root.way, next.way)<200 
  group by root.osm_id, root.name, root.highway, root.railway, root.waterway, root.network;

drop table if exists planet_osm_streets;
create table planet_osm_streets (
  osm_id	int4	not null,
  way_parts	int4[]  not null,
  highway_level int default 0,
  name text,
  primary key(osm_id)
);
drop table if exists planet_osm_streets_parts;
create table planet_osm_streets_parts (
  osm_id	int4	not null,
  part		int4	not null
);
create index planet_osm_streets_parts_pkey on planet_osm_streets_parts(osm_id);
create index planet_osm_streets_parts_part on planet_osm_streets_parts(part);

drop table if exists streets_tmp2;
create table streets_tmp2 (
  osm_id	int4	not null,
  primary key(osm_id)
);

create or replace function collect_streets(root_id int4, name text) returns int as $$
declare
	ids  int4[];
	new  int4[];
	orig int4[];
	res  record;
	done int4[];
	index int:=1;
	x boolean;
	l1   int;
	max_highway_level int:=0;
begin
        select count(*)>0 from streets_tmp2 where osm_id=root_id into x;
	if x then
	  return 0;
	end if;

	ids:=array[root_id];
	done:='{}';
	while done<>ids loop
	  index:=1;
	  while ids[index]>0 loop
	    select count(*)>0 from streets_tmp2 where osm_id=ids[index] into x;
	    if not x then
--	      raise notice 'processing %', ids[index];
	      select array_cat(new, neighbours) from streets_tmp1 where osm_id=ids[index] into new;
	      select highway_level from streets_tmp1 where osm_id=ids[index] into l1;
	      insert into streets_tmp2 values (ids[index]);
	      if l1>max_highway_level then
		max_highway_level=l1;
	      end if;
	    end if;
	    index:=index+1;
	  end loop;

	  done:=array_unique(array_cat(done, ids));
	  ids:=array_unique(new);
	end loop;

--	raise notice 'foo bar %', ids[1];
	insert into planet_osm_streets values (ids[1], ids, max_highway_level, name);
	for index in array_lower(ids, 1)..array_upper(ids, 1) loop
	  insert into planet_osm_streets_parts values (ids[1], ids[index]);
	end loop;
	return 1;
end;
$$ language plpgsql;

begin;
select collect_streets(osm_id, name) from streets_tmp1;
commit;

SELECT AddGeometryColumn('planet_osm_streets', 'way', 900913, 'MULTILINESTRING', 2);
alter table planet_osm_streets add column waytype text;
alter table planet_osm_streets add column network text;

update planet_osm_streets set way=(select ST_Multi(ST_LineMerge(ST_Collect(way))) from planet_osm_line join planet_osm_streets_parts on planet_osm_line.osm_id=planet_osm_streets_parts.part where planet_osm_streets_parts.osm_id=planet_osm_streets.osm_id);

update planet_osm_streets set 
  network=(CASE 
    WHEN highway_level%10=4 THEN 'international' 
    WHEN highway_level%10=3 THEN 'national' 
    WHEN highway_level%10=2 THEN 'region' 
    WHEN highway_level%10=1 THEN 'urban' 
    ELSE 'local' END),
  waytype=(CASE
    WHEN highway_level>=30 THEN 'street'
    WHEN highway_level>=20 THEN 'railway'
    WHEN highway_level>=10 THEN 'waterway'
    ELSE 'same_name'
  END);

alter table planet_osm_streets drop column highway_level;
drop table streets_tmp2;
drop table streets_tmp1;

insert 
  into planet_osm_colls 
  select 
    planet_osm_streets.osm_id, planet_osm_streets.waytype, 
    array['name', 'network'],
    array[name, network]
  from planet_osm_streets;

insert 
  into coll_members
  select 
    planet_osm_streets.osm_id,
    planet_osm_line.osm_id,
    'W', 
  from planet_osm_streets join planet_osm_line on planet_osm_line.osm_id=any(planet_osm_streets.way_parts);

insert 
  into coll_tags
  select 
    planet_osm_streets.osm_id,
    'name',
    name
  from planet_osm_streets;

insert 
  into coll_tags
  select 
    planet_osm_streets.osm_id,
    way_tags.k,
    (to_textarray(way_tags.v))[1]
  from planet_osm_streets join coll_members on coll_members.coll_id=planet_osm_streets.osm_id join way_tags on way_tags.way_id=coll_members.member_id and coll_members.member_type='W' and
  (way_tags.k like 'wikipedia:%' or way_tags.k like 'name:%')
  group by planet_osm_streets.osm_id, planet_osm_streets.way_parts, planet_osm_streets.network, way_tags.k;


insert 
  into coll_tags
  select 
    planet_osm_streets.osm_id,
    'network',
    network
  from planet_osm_streets;

insert 
  into coll_tags
  select 
    planet_osm_streets.osm_id,
    'type',
    waytype
  from planet_osm_streets;

create index planet_osm_streets_way on planet_osm_streets using gist(way);
create index planet_osm_streets_network on planet_osm_streets(network);

update planet_osm_line set "addr:street"=str.name from planet_osm_line str,
(select ob.osm_id, 
  (select osm_id from planet_osm_line findstr where
    findstr.name is not null and findstr.highway is not null
    and 
      makepolygon(geometryfromtext('LINESTRING(' || xmin(ob.way)-200 || ' ' || ymin(ob.way)-200 || ',' || xmax(ob.way)+200 || ' ' || ymin(ob.way)-200 || ',' || xmax(ob.way)+200 || ' ' || ymax(ob.way)+200 || ',' || xmin(ob.way)-200 || ' ' || ymax(ob.way)+200 || ',' || xmin(ob.way)-200 || ' ' || ymin(ob.way)-200 || ')', 900913))&&findstr.way
    order by 
      Distance(geometryfromtext('POINT(' || n1.lon || ' ' || n1.lat || ')', 900913), findstr.way)+
      Distance(geometryfromtext('POINT(' || n2.lon || ' ' || n2.lat || ')', 900913), findstr.way) asc limit 1) as str_id
from planet_osm_line ob
  join way_nodes wn1 on ob.osm_id=wn1.way_id 
  join planet_osm_nodes n1 on wn1.node_id=n1.id and wn1.sequence_id=0
  join way_nodes wn2 on ob.osm_id=wn2.way_id 
  join planet_osm_nodes n2 on wn2.node_id=n2.id and wn2.sequence_id=(select sequence_id from way_nodes wn2a where wn2a.way_id=ob.osm_id 
   and ob."addr:interpolation" in ('odd', 'even', 'all', 'alphabetic') and ob."addr:street" is null
  order by sequence_id desc limit 1)) as t
  where planet_osm_line.osm_id=t.osm_id and str.osm_id=t.str_id;
update planet_osm_point set "addr:street"=str."addr:street" from way_nodes wn left join planet_osm_line str on wn.way_id=str.osm_id where planet_osm_point.osm_id=wn.node_id and planet_osm_point."addr:street" is null and str."addr:street" is not null;
update planet_osm_point set "addr:street"=str.name from planet_osm_line str,
(select ob.osm_id, 
  (select osm_id from planet_osm_line findstr where
    findstr.name is not null and findstr.highway is not null
    and 
      makepolygon(geometryfromtext('LINESTRING(' || xmin(ob.way)-200 || ' ' || ymin(ob.way)-200 || ',' || xmax(ob.way)+200 || ' ' || ymin(ob.way)-200 || ',' || xmax(ob.way)+200 || ' ' || ymax(ob.way)+200 || ',' || xmin(ob.way)-200 || ' ' || ymax(ob.way)+200 || ',' || xmin(ob.way)-200 || ' ' || ymin(ob.way)-200 || ')', 900913))&&findstr.way
    order by 
      Distance(geometryfromtext('POINT(' || n.lon || ' ' || n.lat || ')', 900913), findstr.way) asc limit 1) as str_id
from planet_osm_point ob
  join planet_osm_nodes n on ob.osm_id=n.id
   where ob."addr:housenumber" is not null and ob."addr:street" is null) as t
  where planet_osm_point.osm_id=t.osm_id and str.osm_id=t.str_id;
update planet_osm_polygon set "addr:street"=str.name from planet_osm_line str,
(select ob.osm_id, 
  (select osm_id from planet_osm_line findstr where
    findstr.name is not null and findstr.highway is not null
    and 
      makepolygon(geometryfromtext('LINESTRING(' || xmin(ob.way)-200 || ' ' || ymin(ob.way)-200 || ',' || xmax(ob.way)+200 || ' ' || ymin(ob.way)-200 || ',' || xmax(ob.way)+200 || ' ' || ymax(ob.way)+200 || ',' || xmin(ob.way)-200 || ' ' || ymax(ob.way)+200 || ',' || xmin(ob.way)-200 || ' ' || ymin(ob.way)-200 || ')', 900913))&&findstr.way
    order by 
      Distance(geometryfromtext('POINT(' || n.lon || ' ' || n.lat || ')', 900913), findstr.way) asc limit 1) as str_id
from planet_osm_polygon ob
  join planet_osm_nodes n on ob.osm_id=n.id
   where ob."addr:housenumber" is not null and ob."addr:street" is null) as t
  where planet_osm_polygon.osm_id=t.osm_id and str.osm_id=t.str_id;


insert into coll_members select distinct root.osm_id, next.osm_id, 'W', 'housenumber' from planet_osm_streets root join planet_osm_line next on root.name=next."addr:street" where root.osm_id>0 and next.osm_id>0 and makepolygon(geometryfromtext('LINESTRING(' || xmin(root.way)-200 || ' ' || ymin(root.way)-200 || ',' || xmax(root.way)+200 || ' ' || ymin(root.way)-200 || ',' || xmax(root.way)+200 || ' ' || ymax(root.way)+200 || ',' || xmin(root.way)-200 || ' ' || ymax(root.way)+200 || ',' || xmin(root.way)-200 || ' ' || ymin(root.way)-200 || ')', 900913))&&next.way and Distance(root.way, next.way)<200;
insert into coll_members select distinct root.osm_id, next.osm_id, 'N', 'housenumber' from planet_osm_streets root join planet_osm_point next on root.name=next."addr:street" where root.osm_id>0 and next.osm_id>0 and makepolygon(geometryfromtext('LINESTRING(' || xmin(root.way)-200 || ' ' || ymin(root.way)-200 || ',' || xmax(root.way)+200 || ' ' || ymin(root.way)-200 || ',' || xmax(root.way)+200 || ' ' || ymax(root.way)+200 || ',' || xmin(root.way)-200 || ' ' || ymax(root.way)+200 || ',' || xmin(root.way)-200 || ' ' || ymin(root.way)-200 || ')', 900913))&&next.way and Distance(root.way, next.way)<200;
insert into coll_members select distinct root.osm_id, next.osm_id, 'W', 'housenumber' from planet_osm_streets root join planet_osm_polygon next on root.name=next."addr:street" where root.osm_id>0 and next.osm_id>0 and makepolygon(geometryfromtext('LINESTRING(' || xmin(root.way)-200 || ' ' || ymin(root.way)-200 || ',' || xmax(root.way)+200 || ' ' || ymin(root.way)-200 || ',' || xmax(root.way)+200 || ' ' || ymax(root.way)+200 || ',' || xmin(root.way)-200 || ' ' || ymax(root.way)+200 || ',' || xmin(root.way)-200 || ' ' || ymin(root.way)-200 || ')', 900913))&&next.way and Distance(root.way, next.way)<200;

drop table if exists housenumber;
create table housenumber (
node_id int4 null,
way_id	int4 null,
coll_id int4 null,
number text
);
SELECT AddGeometryColumn('housenumber', 'way', 900913, 'LINESTRING', 2);
insert into housenumber 
(select osm_id, null, (select coll_id from coll_members cm where cm.member_id=osm_id and cm.member_type='N' limit 1), number, (CASE WHEN length(line)>0 THEN translate(scale(translate(line, -x(centroid(line)), -y(centroid(line))), 100/length(line), 100/length(line)), x(poi_way), y(poi_way)) END) as way from
(select osm_id, number, poi_way,
  line_interpolate_point(next_way, pos) as next_poi,
  makeline((CASE 
    WHEN pos-0.001/len<0 THEN line_interpolate_point(next_way, pos)
    ELSE line_interpolate_point(next_way, pos-0.001/len)
  END), 
  (CASE 
    WHEN pos+0.001/len>=1 THEN line_interpolate_point(next_way, pos)
    ELSE line_interpolate_point(next_way, pos+0.001/len)
  END)) as line
from 
(select t.osm_id, number, poi_way, next_way, line_locate_point(next_way, poi_way) as pos, length(next_way) as len from (
select poi.osm_id, poi."addr:housenumber" as number, poi.way as poi_way, 
  (select l.way
    from coll_members find_street1 join coll_members find_street2 on find_street2.coll_id=find_street1.coll_id and find_street2.member_type='W' join planet_osm_line l on l.osm_id=find_street2.member_id where find_street1.member_id=poi.osm_id and find_street1.member_type='N' order by distance(poi.way, l.way) asc limit 1) as next_way from planet_osm_point poi where poi."addr:housenumber" is not null and poi."addr:street" is not null) as t) as t2) as t3);

insert into housenumber 
(select null, osm_id, (select coll_id from coll_members cm where cm.member_id=osm_id and cm.member_type='W' limit 1), number, (CASE WHEN length(line)>0 THEN translate(scale(translate(line, -x(centroid(line)), -y(centroid(line))), 100/length(line), 100/length(line)), x(poi_way), y(poi_way)) END) as way from
(select osm_id, number, poi_way,
  line_interpolate_point(next_way, pos) as next_poi,
  makeline((CASE 
    WHEN pos-0.001/len<0 THEN line_interpolate_point(next_way, pos)
    ELSE line_interpolate_point(next_way, pos-0.001/len)
  END), 
  (CASE 
    WHEN pos+0.001/len>=1 THEN line_interpolate_point(next_way, pos)
    ELSE line_interpolate_point(next_way, pos+0.001/len)
  END)) as line
from 
(select t.osm_id, number, poi_way, next_way, line_locate_point(next_way, poi_way) as pos, length(next_way) as len from (
select poi.osm_id, poi."addr:housenumber" as number, Centroid(poi.way) as poi_way, 
  (select l.way
    from coll_members find_street1 join coll_members find_street2 on find_street2.coll_id=find_street1.coll_id and find_street2.member_type='W' join planet_osm_line l on l.osm_id=find_street2.member_id where find_street1.member_id=poi.osm_id and find_street1.member_type='W' order by distance(Centroid(poi.way), l.way) asc limit 1) as next_way from planet_osm_polygon poi where poi."addr:housenumber" is not null and poi."addr:street" is not null) as t) as t2) as t3);
create index housenumber_way on housenumber using gist(way);
create index housenumber_node_id on housenumber(node_id);
create index housenumber_way_id on housenumber(way_id);

drop table if exists housenumber_line;
create table housenumber_line (
way_id	int4 null,
coll_id int4 null,
first int,
last int,
interpolation text
);

insert into housenumber_line
select osm_id, coll_id, 
(CASE WHEN first<last THEN first ELSE last END),
(CASE WHEN first<last THEN last ELSE first END),
interpolation from
(select osm_id, coll_id, cast(first as int) as first, cast(last as int) as last, interpolation from
  (select osm_id, 
    (select coll_id from coll_members cm where cm.member_id=osm_id and cm.member_type='W' limit 1) as coll_id,
    (select "addr:housenumber" from way_nodes join planet_osm_point on way_nodes.way_id=planet_osm_line.osm_id and way_nodes.node_id=planet_osm_point.osm_id order by way_nodes.sequence_id asc limit 1) as first,
    (select "addr:housenumber" from way_nodes join planet_osm_point on way_nodes.way_id=planet_osm_line.osm_id and way_nodes.node_id=planet_osm_point.osm_id order by way_nodes.sequence_id desc limit 1) as last,
    "addr:interpolation" as interpolation
    from planet_osm_line where "addr:interpolation" in ('odd', 'even', 'all', 'alphabetic')) as t
  where first similar to '[0-9]+' and last similar to '[0-9]+') as t1;

--create index 
