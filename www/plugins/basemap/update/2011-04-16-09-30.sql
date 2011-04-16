delete from classify_hmatch where "type"='basemap_waterarea';

insert into classify_hmatch values ( 'basemap_landuse',
  'waterway=>dock',
  null,
  '#landuse=>water',
  2
);
insert into classify_hmatch values ( 'basemap_landuse',
  'waterway=>riverbank',
  null,
  '#landuse=>water',
  2
);
insert into classify_hmatch values ( 'basemap_landuse',
  'landuse=>water',
  null,
  '#landuse=>water',
  1
);
insert into classify_hmatch values ( 'basemap_landuse',
  'landuse=>reservoir',
  null,
  '#landuse=>water',
  1
);
insert into classify_hmatch values ( 'basemap_landuse',
  'landuse=>lake',
  null,
  '#landuse=>water',
  1
);
insert into classify_hmatch values ( 'basemap_landuse',
  'landuse=>basin',
  null,
  '#landuse=>water',
  1
);
insert into classify_hmatch values ( 'basemap_landuse',
  'natural=>water',
  null,
  '#landuse=>water',
  0
);
insert into classify_hmatch values ( 'basemap_landuse',
  'natural=>land',
  null,
  '#landuse=>land',
  0
);
