create table my_maps_map (
  id		text	not null,
  tags		hstore	,
  primary key(id)
);

create table my_maps_item (
  map_id	text	not null,
  id		text	not null,
  tags		hstore	,
  primary key(map_id, id)
);
select AddGeometryColumn('my_maps_item', 'way', 900913, 'GEOMETRY', 2);
