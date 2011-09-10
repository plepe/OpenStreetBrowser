delete from classify_hmatch where type='basemap_highway' and match->'highway' in ('steps', 'cycleway', 'footway', 'bridleway', 'path') or match @> 'railway=>platform';

insert into classify_hmatch values ( 'basemap_highway',
  'highway=>steps',
  null,
  '#highway_type=>path, #highway_subtype=>t1, #highway_level=>2'
);
insert into classify_hmatch values ( 'basemap_highway',
  'highway=>cycleway',
  null,
  '#highway_type=>path, #highway_subtype=>t2, #highway_level=>2'
);
insert into classify_hmatch values ( 'basemap_highway',
  'highway=>footway',
  null,
  '#highway_type=>path, #highway_subtype=>t2, #highway_level=>2'
);
insert into classify_hmatch values ( 'basemap_highway',
  'highway=>bridleway',
  null,
  '#highway_type=>path, #highway_subtype=>t2, #highway_level=>2'
);
insert into classify_hmatch values ( 'basemap_highway',
  'railway=>platform',
  null,
  '#highway_type=>path, #highway_subtype=>t2, #highway_level=>2'
);
insert into classify_hmatch values ( 'basemap_highway',
  'highway=>path',
  null,
  '#highway_type=>path, #highway_subtype=>t3, #highway_level=>2'
);
