create table renderd_tiles (
  host		varchar(256)		null,
  map		varchar(256)		not null,
  zoom		int2			not null,
  x_min		int4			not null,
  x_max		int4			not null,
  y_min		int4			not null,
  y_max		int4			not null,
  time		float			not null,
  date		timestamp		not null,
  previous	timestamp[]		not null default Array[]::timestamp[],
  primary key(host, map, zoom, x_min, y_min)
);
