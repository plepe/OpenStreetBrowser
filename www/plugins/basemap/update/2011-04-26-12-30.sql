update classify_hmatch set type_match='form=>line' where type='basemap_highway' and match @> 'highway=>pedestrian';

-- basemap_highway_poly
insert into classify_hmatch values ( 'basemap_highway_poly',
  'highway=>pedestrian',
  null,
  '#highway_polytype=>pedestrian, #highway_level=>3'
);
insert into classify_hmatch values ( 'basemap_highway_poly',
  'amenity=>parking',
  null,
  '#highway_polytype=>parking, #highway_level=>0'
);

