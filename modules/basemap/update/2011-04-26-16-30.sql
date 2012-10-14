-- area_text
insert into classify_hmatch values ('basemap_area_text',
  'natural=>water',
  null,
  '#area_text=>water'
);
insert into classify_hmatch values ('basemap_area_text',
  'natural=>bay',
  null,
  '#area_text=>water'
);
insert into classify_hmatch values ('basemap_area_text',
  'highway=>pedestrian',
  'tunnel',
  '#area_text=>pedestrian_tunnel',
  1
);
insert into classify_hmatch values ('basemap_area_text',
  'highway=>pedestrian',
  null,
  '#area_text=>pedestrian',
  0
);
insert into classify_hmatch values ('basemap_area_text',
  'leisure=>park',
  null,
  '#area_text=>park'
);
