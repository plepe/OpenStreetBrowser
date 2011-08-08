delete from classify_hmatch where "type"='basemap_highway' and (
  "match" @> 'railway=>tram' or
  "match" @> 'railway=>light_rail' or
  "match" @> 'railway=>narrow_gauge' or
  "match" @> 'railway=>rail' or
  "match" @> 'railway=>subway' or
  "match" @> 'railway=>preserved' or
  "match" @> 'railway=>monorail');

insert into classify_hmatch values ( 'basemap_highway',
  'railway=>tram',
  null,
  '#highway_type=>railway, #highway_subtype=>t1, #highway_level=>1, #highway_extract_level=>16',
  -1
);
insert into classify_hmatch values ( 'basemap_highway',
  'railway=>light_rail',
  null,
  '#highway_type=>railway, #highway_subtype=>t1, #highway_level=>1, #highway_extract_level=>16',
  -1
);
insert into classify_hmatch values ( 'basemap_highway',
  'railway=>narrow_gauge',
  null,
  '#highway_type=>railway, #highway_subtype=>t1, #highway_level=>1, #highway_extract_level=>15',
  -1
);
insert into classify_hmatch values ( 'basemap_highway',
  'railway=>rail, usage=>main',
  null,
  '#highway_type=>railway, #highway_subtype=>t2, #highway_level=>1, #highway_extract_level=>18',
  -1
);
insert into classify_hmatch values ( 'basemap_highway',
  'railway=>rail',
  null,
  '#highway_type=>railway, #highway_subtype=>t2, #highway_level=>1, #highway_extract_level=>17',
  -1
);
insert into classify_hmatch values ( 'basemap_highway',
  'railway=>subway',
  null,
  '#highway_type=>railway, #highway_subtype=>t2, #highway_level=>1, #highway_extract_level=>17',
  -1
);
insert into classify_hmatch values ( 'basemap_highway',
  'railway=>preserved',
  null,
  '#highway_type=>railway, #highway_subtype=>t2, #highway_level=>1, #highway_extract_level=>1',
  -1
);
insert into classify_hmatch values ( 'basemap_highway',
  'railway=>monorail',
  null,
  '#highway_type=>railway, #highway_subtype=>t2, #highway_level=>1, #highway_extract_level=>17',
  -1
);
