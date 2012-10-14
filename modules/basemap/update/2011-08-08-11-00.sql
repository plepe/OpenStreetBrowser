delete from classify_hmatch where "type"='basemap_highway' and (
  "match" @> 'highway=>motorway_link' or
  "match" @> 'highway=>trunk_link' or
  "match" @> 'highway=>primary_link');

insert into classify_hmatch values ( 'basemap_highway',
  'highway=>motorway_link',
  null,
  '#highway_type=>motorway, #highway_subtype=>t2, #highway_level=>9'
);
insert into classify_hmatch values ( 'basemap_highway',
  'highway=>trunk_link',
  null,
  '#highway_type=>motorway, #highway_subtype=>t4, #highway_level=>9'
);
insert into classify_hmatch values ( 'basemap_highway',
  'highway=>primary_link',
  null,
  '#highway_type=>major, #highway_subtype=>t2, #highway_level=>8'
);
