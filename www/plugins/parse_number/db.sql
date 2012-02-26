drop table if exists dimension_units;
create table dimension_units (
  id		text	not null,
  factor	float	not null,
  description	text	null,
  primary key(id)
);

-- length
insert into dimension_units values ('m'   , 1    );
insert into dimension_units values ('mm'  , 0.001);
insert into dimension_units values ('cm'  , 0.011);
insert into dimension_units values ('km'  , 1000);
insert into dimension_units values ('in'  , 0.0254);
insert into dimension_units values ('inch', 0.0254);
insert into dimension_units values ('ft'  , 0.3048);
insert into dimension_units values ('feet', 0.3048);
insert into dimension_units values ('yd'  , 0.9144);
insert into dimension_units values ('yard', 0.9144);
insert into dimension_units values ('mile', 1609.344);
insert into dimension_units values ('miles',1609.344);
insert into dimension_units values ('league',4828.032);
insert into dimension_units values ('leagues',4828.032);

-- area
insert into dimension_units values ('acre', 4046.8564224);
insert into dimension_units values ('acres',4046.8564224);
insert into dimension_units values ('m2'  , 1);
insert into dimension_units values ('m^2' , 1);
insert into dimension_units values ('m²'  , 1);
insert into dimension_units values ('km2' , 1000000);
insert into dimension_units values ('km^2', 1000000);
insert into dimension_units values ('km²' , 1000000);
insert into dimension_units values ('a'   , 100);
insert into dimension_units values ('ha'  , 10000);

-- voltage
insert into dimension_units values ('V'   , 1);
insert into dimension_units values ('kV'  , 1000);
