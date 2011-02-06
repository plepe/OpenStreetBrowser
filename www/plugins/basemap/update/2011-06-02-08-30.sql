-- basemap_highway
insert into classify_hmatch values ( 'basemap_highway',
  'highway=>motorway',
  null,
  '#highway_type=>motorway, #highway_subtype=>t1, #highway_level=>21'
);
insert into classify_hmatch values ( 'basemap_highway',
  'highway=>trunk',
  null,
  '#highway_type=>motorway, #highway_subtype=>t2, #highway_level=>21'
);
insert into classify_hmatch values ( 'basemap_highway',
  'highway=>motorway_link',
  null,
  '#highway_type=>motorway, #highway_subtype=>t3, #highway_level=>20'
);
insert into classify_hmatch values ( 'basemap_highway',
  'highway=>trunk_link',
  null,
  '#highway_type=>motorway, #highway_subtype=>t4, #highway_level=>20'
);
insert into classify_hmatch values ( 'basemap_highway',
  'highway=>primary',
  null,
  '#highway_type=>major, #highway_subtype=>t1, #highway_level=>12'
);
insert into classify_hmatch values ( 'basemap_highway',
  'highway=>primary_link',
  null,
  '#highway_type=>major, #highway_subtype=>t2, #highway_level=>12'
);
insert into classify_hmatch values ( 'basemap_highway',
  'highway=>secondary',
  null,
  '#highway_type=>major, #highway_subtype=>t3, #highway_level=>11'
);
insert into classify_hmatch values ( 'basemap_highway',
  'highway=>tertiary',
  null,
  '#highway_type=>major, #highway_subtype=>t4, #highway_level=>10'
);
insert into classify_hmatch values ( 'basemap_highway',
  'highway=>unclassified',
  null,
  '#highway_type=>minor, #highway_level=>4'
);
insert into classify_hmatch values ( 'basemap_highway',
  'highway=>road',
  null,
  '#highway_type=>minor, #highway_level=>4'
);
insert into classify_hmatch values ( 'basemap_highway',
  'highway=>residential',
  null,
  '#highway_type=>minor, #highway_level=>4'
);
insert into classify_hmatch values ( 'basemap_highway',
  'highway=>living_street',
  null,
  '#highway_type=>pedestrian, #highway_level=>3'
);
insert into classify_hmatch values ( 'basemap_highway',
  'highway=>pedestrian',
  null,
  '#highway_type=>pedestrian, #highway_polytype=>pedestrian, #highway_level=>3'
);
insert into classify_hmatch values ( 'basemap_highway',
  'highway=>byway',
  null,
  '#highway_type=>pedestrian, #highway_level=>3'
);
insert into classify_hmatch values ( 'basemap_highway',
  'highway=>service',
  null,
  '#highway_type=>service, #highway_subtype=>t1, #highway_level=>3'
);
insert into classify_hmatch values ( 'basemap_highway',
  'highway=>bus_guideway',
  null,
  '#highway_type=>service, #highway_subtype=>t1, #highway_level=>3'
);
insert into classify_hmatch values ( 'basemap_highway',
  'highway=>track',
  null,
  '#highway_type=>service, #highway_subtype=>t2, #highway_level=>2'
);
insert into classify_hmatch values ( 'basemap_highway',
  'highway=>path',
  null,
  '#highway_type=>path, #highway_level=>2'
);
insert into classify_hmatch values ( 'basemap_highway',
  'highway=>cycleway',
  null,
  '#highway_type=>path, #highway_level=>2'
);
insert into classify_hmatch values ( 'basemap_highway',
  'highway=>footway',
  null,
  '#highway_type=>path, #highway_level=>2'
);
insert into classify_hmatch values ( 'basemap_highway',
  'highway=>bridleway',
  null,
  '#highway_type=>path, #highway_level=>2'
);
insert into classify_hmatch values ( 'basemap_highway',
  'highway=>steps',
  null,
  '#highway_type=>path, #highway_subtype=>t1, #highway_level=>2'
);
insert into classify_hmatch values ( 'basemap_highway',
  'railway=>platform',
  null,
  '#highway_type=>path, #highway_level=>2'
);
insert into classify_hmatch values ( 'basemap_highway',
  'railway=>tram',
  null,
  '#highway_type=>railway, #highway_subtype=>t1, #highway_level=>1',
  -1
);
insert into classify_hmatch values ( 'basemap_highway',
  'railway=>light_rail',
  null,
  '#highway_type=>railway, #highway_subtype=>t1, #highway_level=>1',
  -1
);
insert into classify_hmatch values ( 'basemap_highway',
  'railway=>narrow_gauge',
  null,
  '#highway_type=>railway, #highway_subtype=>t1, #highway_level=>1',
  -1
);
insert into classify_hmatch values ( 'basemap_highway',
  'railway=>rail',
  null,
  '#highway_type=>railway, #highway_subtype=>t2, #highway_level=>1',
  -1
);
insert into classify_hmatch values ( 'basemap_highway',
  'railway=>subway',
  null,
  '#highway_type=>railway, #highway_subtype=>t2, #highway_level=>1',
  -1
);
insert into classify_hmatch values ( 'basemap_highway',
  'railway=>preserved',
  null,
  '#highway_type=>railway, #highway_subtype=>t2, #highway_level=>1',
  -1
);
insert into classify_hmatch values ( 'basemap_highway',
  'railway=>monorail',
  null,
  '#highway_type=>railway, #highway_subtype=>t2, #highway_level=>1',
  -1
);
insert into classify_hmatch values ( 'basemap_highway',
  'aeroway=>runway',
  null,
  '#highway_type=>aeroway, #highway_subtype=>t1, #highway_level=>0'
);
insert into classify_hmatch values ( 'basemap_highway',
  'aeroway=>taxiway',
  null,
  '#highway_type=>aeroway, #highway_subtype=>t2, #highway_level=>0'
);
insert into classify_hmatch values ( 'basemap_highway',
  'waterway=>river',
  null,
  '#highway_type=>waterway, #highway_subtype=>t1, #highway_level=>0'
);
insert into classify_hmatch values ( 'basemap_highway',
  'waterway=>canal',
  null,
  '#highway_type=>waterway, #highway_subtype=>t2, #highway_level=>0'
);
insert into classify_hmatch values ( 'basemap_highway',
  'waterway=>stream',
  null,
  '#highway_type=>waterway, #highway_subtype=>t3, #highway_level=>0'
);
insert into classify_hmatch values ( 'basemap_highway',
  'barrier=>wall',
  null,
  '#highway_type=>barrier, #highway_subtype=>t1, #highway_level=>0'
);
insert into classify_hmatch values ( 'basemap_highway',
  'barrier=>city_wall',
  null,
  '#highway_type=>barrier, #highway_subtype=>t1, #highway_level=>0',
  -1
);
insert into classify_hmatch values ( 'basemap_highway',
  '',
  'barrier',
  '#highway_type=>barrier, #highway_level=>0',
  -2
);
-- more barrier=>*
insert into classify_hmatch values ( 'basemap_highway',
  'natural=>cliff',
  null,
  '#highway_type=>natural, #highway_subtype=>t1, #highway_level=>0'
);
insert into classify_hmatch values ( 'basemap_highway',
  'power=>line',
  null,
  '#highway_type=>power, #highway_subtype=>t1, #highway_level=>0'
);
insert into classify_hmatch values ( 'basemap_highway',
  'power=>minor_line',
  null,
  '#highway_type=>power, #highway_subtype=>t6, #highway_level=>0'
);
insert into classify_hmatch values ( 'basemap_highway',
  'man_made=>pipeline, type=>water',
  null,
  '#highway_type=>pipeline, #highway_subtype=>t1, #highway_level=>0'
);
insert into classify_hmatch values ( 'basemap_highway',
  'man_made=>pipeline, type=>oil',
  null,
  '#highway_type=>pipeline, #highway_subtype=>t2, #highway_level=>0'
);
insert into classify_hmatch values ( 'basemap_highway',
  'man_made=>pipeline, type=>gas',
  null,
  '#highway_type=>pipeline, #highway_subtype=>t3, #highway_level=>0'
);
insert into classify_hmatch values ( 'basemap_highway',
  'man_made=>pipeline, type=>sewage',
  null,
  '#highway_type=>pipeline, #highway_subtype=>t4, #highway_level=>0'
);
insert into classify_hmatch values ( 'basemap_highway',
  'man_made=>pipeline, type=>heat',
  null,
  '#highway_type=>pipeline, #highway_subtype=>t5, #highway_level=>0'
);
insert into classify_hmatch values ( 'basemap_highway',
  'man_made=>pipeline, type=>hot_water',
  null,
  '#highway_type=>pipeline, #highway_subtype=>t5, #highway_level=>0'
);
insert into classify_hmatch values ( 'basemap_highway',
  'amenity=>parking',
  null,
  '#highway_polytype=>parking, #highway_level=>0'
);

-- bridge
insert into classify_hmatch values ( 'basemap_bridge',
  'bridge=>yes',
  null,
  '#bridge=>yes'
);
insert into classify_hmatch values ( 'basemap_bridge',
  'bridge=>true',
  null,
  '#bridge=>yes'
);
insert into classify_hmatch values ( 'basemap_bridge',
  'bridge=>1',
  null,
  '#bridge=>yes'
);
insert into classify_hmatch values ( 'basemap_bridge',
  'bridge=>viaduct',
  null,
  '#bridge=>yes'
);
insert into classify_hmatch values ( 'basemap_bridge',
  'bridge=>swing',
  null,
  '#bridge=>yes'
);
insert into classify_hmatch values ( 'basemap_bridge',
  'bridge=>aqueduct',
  null,
  '#bridge=>yes'
);
insert into classify_hmatch values ( 'basemap_bridge',
  '',
  null,
  '#bridge=>no',
  -1
);

-- tunnel
insert into classify_hmatch values ( 'basemap_tunnel',
  'tunnel=>yes',
  null,
  '#tunnel=>yes'
);
insert into classify_hmatch values ( 'basemap_tunnel',
  'tunnel=>true',
  null,
  '#tunnel=>yes'
);
insert into classify_hmatch values ( 'basemap_tunnel',
  'tunnel=>1',
  null,
  '#tunnel=>yes'
);
insert into classify_hmatch values ( 'basemap_tunnel',
  '',
  null,
  '#tunnel=>no',
  -1
);

-- railway
insert into classify_hmatch values ( 'basemap_railway',
  'railway=>tram',
  null,
  '#railway=>tram'
);
insert into classify_hmatch values ( 'basemap_railway',
  'railway=>light_rail',
  null,
  '#railway=>tram'
);
insert into classify_hmatch values ( 'basemap_railway',
  'railway=>rail',
  null,
  '#railway=>rail'
);
insert into classify_hmatch values ( 'basemap_railway',
  'railway=>narrow_gauge',
  null,
  '#railway=>rail'
);
insert into classify_hmatch values ( 'basemap_railway',
  'railway=>monorail',
  null,
  '#railway=>rail'
);
insert into classify_hmatch values ( 'basemap_railway',
  'railway=>subway',
  null,
  '#railway=>rail'
);

-- railway_tracks
insert into classify_hmatch values ( 'basemap_railway_tracks',
  'railway=>tram, tracks=>left',
  null,
  '#railway_tracks=>left',
  1
);
insert into classify_hmatch values ( 'basemap_railway_tracks',
  'railway=>tram, tracks=>right',
  null,
  '#railway_tracks=>right',
  1
);
insert into classify_hmatch values ( 'basemap_railway_tracks',
  'railway=>light_rail, tracks=>left',
  null,
  '#railway_tracks=>left',
  1
);
insert into classify_hmatch values ( 'basemap_railway_tracks',
  'railway=>light_rail, tracks=>right',
  null,
  '#railway_tracks=>right',
  1
);
insert into classify_hmatch values ( 'basemap_railway_tracks',
  'railway=>subway',
  null,
  '#railway_tracks=>double',
  0
);
insert into classify_hmatch values ( 'basemap_railway_tracks',
  '',
  null,
  '#railway_tracks=>single',
  -1 
);
insert into classify_hmatch values ( 'basemap_railway_tracks',
  'tracks=>1',
  null,
  '#railway_tracks=>single',
  1
);
insert into classify_hmatch values ( 'basemap_railway_tracks',
  'tracks=>single',
  null,
  '#railway_tracks=>single',
  1
);
insert into classify_hmatch values ( 'basemap_railway_tracks',
  'tracks=>2',
  null,
  '#railway_tracks=>double',
  1
);
insert into classify_hmatch values ( 'basemap_railway_tracks',
  'tracks=>double',
  null,
  '#railway_tracks=>double',
  1
);
insert into classify_hmatch values ( 'basemap_railway_tracks',
  'tracks=>3',
  null,
  '#railway_tracks=>multiple',
  1
);
insert into classify_hmatch values ( 'basemap_railway_tracks',
  'tracks=>4',
  null,
  '#railway_tracks=>multiple',
  1
);
insert into classify_hmatch values ( 'basemap_railway_tracks',
  'tracks=>5',
  null,
  '#railway_tracks=>multiple',
  1
);
insert into classify_hmatch values ( 'basemap_railway_tracks',
  'tracks=>6',
  null,
  '#railway_tracks=>multiple',
  1
);

