delete from classify_hmatch where type='basemap_building' and match ? 'barrier';
delete from classify_hmatch where type='basemap_highway' and key_exists='barrier';
delete from classify_hmatch where type='basemap_highway' and match ? 'barrier';

insert into classify_hmatch values ( 'basemap_highway',
  'barrier=>city_wall',
  null,
  '#highway_type=>barrier, #highway_subtype=>t1, #highway_level=>0',
  -1
);
insert into classify_hmatch values ( 'basemap_highway',
  'barrier=>wall',
  null,
  '#highway_type=>barrier, #highway_subtype=>t2, #highway_level=>0'
);
insert into classify_hmatch values ( 'basemap_highway',
  'barrier=>retaining_wall',
  null,
  '#highway_type=>barrier, #highway_subtype=>t2, #highway_level=>0'
);
insert into classify_hmatch values ( 'basemap_highway',
  'barrier=>fence',
  null,
  '#highway_type=>barrier, #highway_subtype=>t3, #highway_level=>0',
  -1
);
insert into classify_hmatch values ( 'basemap_highway',
  'barrier=>hedge',
  null,
  '#highway_type=>barrier, #highway_subtype=>t4, #highway_level=>0',
  -1
);
