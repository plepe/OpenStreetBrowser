-- basemap_water_area
insert into classify_hmatch values ('basemap_water_area',
  '',
  'waterway',
  '#water_area=>[waterway]',
  2
);
insert into classify_hmatch values ('basemap_water_area',
  '',
  'landuse',
  '#water_area=>[landuse]',
  1
);
insert into classify_hmatch values ('basemap_water_area',
  '',
  'natural',
  '#water_area=>[natural]',
  0
);
