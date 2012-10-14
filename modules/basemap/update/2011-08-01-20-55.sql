delete from classify_hmatch where "type"='basemap_highway' and "match" @> 'highway=>motorway_link';
delete from classify_hmatch where "type"='basemap_highway' and "match" @> 'highway=>trunk';
insert into classify_hmatch values ( 'basemap_highway',
  'highway=>motorway_link',
  null,
  '#highway_type=>motorway, #highway_subtype=>t2, #highway_level=>20'
);
insert into classify_hmatch values ( 'basemap_highway',
  'highway=>trunk',
  null,
  '#highway_type=>motorway, #highway_subtype=>t3, #highway_level=>21'
);

