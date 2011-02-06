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

-- basemap_landuse
insert into classify_hmatch values ( 'basemap_landuse',
  'leisure=>park',
  null,
  '#landuse=>park'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'landuse=>village_green',
  null,
  '#landuse=>park'
);

insert into classify_hmatch values ( 'basemap_landuse',
  'landuse=>recreation_ground',
  null,
  '#landuse=>park'
);

insert into classify_hmatch values ( 'basemap_landuse',
  'landuse=>grass',
  null,
  '#landuse=>park'
);

insert into classify_hmatch values ( 'basemap_landuse',
  'leisure=>golf_course',
  null,
  '#landuse=>sport, #landuse_subtype=>t0'
);

insert into classify_hmatch values ( 'basemap_landuse',
  'leisure=>playground',
  null,
  '#landuse=>sport, #landuse_subtype=>t0'
);

insert into classify_hmatch values ( 'basemap_landuse',
  'leisure=>sports_centre',
  null,
  '#landuse=>sport, #landuse_subtype=>t0'
);

insert into classify_hmatch values ( 'basemap_landuse',
  'leisure=>track',
  null,
  '#landuse=>sport, #landuse_subtype=>t0'
);

insert into classify_hmatch values ( 'basemap_landuse',
  'leisure=>pitch',
  null,
  '#landuse=>sport, #landuse_subtype=>t0'
);

insert into classify_hmatch values ( 'basemap_landuse',
  'leisure=>water_park',
  null,
  '#landuse=>sport, #landuse_subtype=>t0'
);

insert into classify_hmatch values ( 'basemap_landuse',
  'leisure=>piste',
  null,
  '#landuse=>sport, #landuse_subtype=>t1'
);

insert into classify_hmatch values ( 'basemap_landuse',
  'leisure=>nature_reserve',
  null,
  '#landuse=>nature_reserve'
);

insert into classify_hmatch values ( 'basemap_landuse',
  'landuse=>wood',
  null,
  '#landuse=>natural0, #landuse_subtype=>t0'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'landuse=>forest',
  null,
  '#landuse=>natural0, #landuse_subtype=>t0'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'natural=>wood',
  null,
  '#landuse=>natural0, #landuse_subtype=>t0'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'natural=>scrub',
  null,
  '#landuse=>natural0, #landuse_subtype=>t0'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'natural=>wetland',
  null,
  '#landuse=>natural0, #landuse_subtype=>t1'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'natural=>marsh',
  null,
  '#landuse=>natural0, #landuse_subtype=>t1'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'natural=>glacier',
  null,
  '#landuse=>natural0, #landuse_subtype=>t2'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'natural=>scree',
  null,
  '#landuse=>natural0, #landuse_subtype=>t3'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'natural=>heath',
  null,
  '#landuse=>natural0, #landuse_subtype=>t3'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'natural=>mud',
  null,
  '#landuse=>natural1, #landuse_subtype=>t0'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'natural=>mud',
  null,
  '#landuse=>natural1, #landuse_subtype=>t0'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'natural=>beach',
  null,
  '#landuse=>natural1, #landuse_subtype=>t1'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'natural=>cliff',
  null,
  '#landuse=>natural1, #landuse_subtype=>t2'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'natural=>rock',
  null,
  '#landuse=>natural1, #landuse_subtype=>t2'
);

insert into classify_hmatch values ( 'basemap_landuse',
  'landuse=>cemetery',
  null,
  '#landuse=>cemetery'
);

insert into classify_hmatch values ( 'basemap_landuse',
  'leisure=>common',
  null,
  '#landuse=>garden'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'leisure=>garden',
  null,
  '#landuse=>garden'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'landuse=>meadow',
  null,
  '#landuse=>garden'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'natural=>meadow',
  null,
  '#landuse=>garden'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'natural=>fell',
  null,
  '#landuse=>garden'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'landuse=>farm',
  null,
  '#landuse=>garden'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'landuse=>greenhouse_horticulture',
  null,
  '#landuse=>garden'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'landuse=>farmyard',
  null,
  '#landuse=>garden'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'landuse=>farmland',
  null,
  '#landuse=>garden'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'landuse=>vineyard',
  null,
  '#landuse=>garden'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'landuse=>orchard',
  null,
  '#landuse=>garden'
);

insert into classify_hmatch values ( 'basemap_landuse',
  'landuse=>quarry',
  null,
  '#landuse=>industrial, #landuse_subtype=>t0'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'landuse=>landfill',
  null,
  '#landuse=>industrial, #landuse_subtype=>t0'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'landuse=>brownfield',
  null,
  '#landuse=>industrial, #landuse_subtype=>t0'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'landuse=>railway',
  null,
  '#landuse=>industrial, #landuse_subtype=>t0'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'landuse=>construction',
  null,
  '#landuse=>industrial, #landuse_subtype=>t0'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'landuse=>industrial',
  null,
  '#landuse=>industrial, #landuse_subtype=>t0'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'amenity=>bus_station',
  null,
  '#landuse=>industrial, #landuse_subtype=>t0'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'aeroway=>aerodrome',
  null,
  '#landuse=>industrial, #landuse_subtype=>t0'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'aeroway=>apron',
  null,
  '#landuse=>industrial, #landuse_subtype=>t0'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'power=>station',
  null,
  '#landuse=>industrial, #landuse_subtype=>t0'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'power=>sub_station',
  null,
  '#landuse=>industrial, #landuse_subtype=>t0'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'power=>generator',
  null,
  '#landuse=>industrial, #landuse_subtype=>t0'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'landuse=>military',
  null,
  '#landuse=>industrial, #landuse_subtype=>t1'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'military=>barracks',
  null,
  '#landuse=>industrial, #landuse_subtype=>t1'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'military=>airfield',
  null,
  '#landuse=>industrial, #landuse_subtype=>t1'
);

insert into classify_hmatch values ( 'basemap_landuse',
  'landuse=>residential',
  null,
  '#landuse=>residential'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'landuse=>allotments',
  null,
  '#landuse=>residential'
);

insert into classify_hmatch values ( 'basemap_landuse',
  '',
  'historic',
  '#landuse=>historic'
);

insert into classify_hmatch values ( 'basemap_landuse',
  '',
  'tourism',
  '#landuse=>tourism'
);

insert into classify_hmatch values ( 'basemap_landuse',
  'landuse=>school',
  null,
  '#landuse=>education'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'amenity=>college',
  null,
  '#landuse=>education'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'amenity=>cinema',
  null,
  '#landuse=>education'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'amenity=>kindergarten',
  null,
  '#landuse=>education'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'amenity=>library',
  null,
  '#landuse=>education'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'amenity=>school',
  null,
  '#landuse=>education'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'amenity=>theatre',
  null,
  '#landuse=>education'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'amenity=>arts_centre',
  null,
  '#landuse=>education'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'amenity=>university',
  null,
  '#landuse=>education'
);

insert into classify_hmatch values ( 'basemap_landuse',
  'amenity=>hospital',
  null,
  '#landuse=>emergency'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'amenity=>emergency_phone',
  null,
  '#landuse=>emergency'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'amenity=>fire_station',
  null,
  '#landuse=>emergency'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'amenity=>police',
  null,
  '#landuse=>emergency'
);

insert into classify_hmatch values ( 'basemap_landuse',
  'amenity=>pharmacy',
  null,
  '#landuse=>health'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'amenity=>baby_hatch',
  null,
  '#landuse=>health'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'amenity=>dentist',
  null,
  '#landuse=>health'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'amenity=>doctors',
  null,
  '#landuse=>health'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'amenity=>veterinary',
  null,
  '#landuse=>health'
);

insert into classify_hmatch values ( 'basemap_landuse',
  'amenity=>government',
  null,
  '#landuse=>public'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'amenity=>gouvernment',
  null,
  '#landuse=>public'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'amenity=>public_building',
  null,
  '#landuse=>public'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'amenity=>court_house',
  null,
  '#landuse=>public'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'amenity=>embassy',
  null,
  '#landuse=>public'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'amenity=>prison',
  null,
  '#landuse=>public'
);
insert into classify_hmatch values ( 'basemap_landuse',
  'amenity=>townhall',
  null,
  '#landuse=>public'
);

insert into classify_hmatch values ( 'basemap_landuse',
  'amenity=>marketplace',
  null,
  '#landuse=>shop'
);
insert into classify_hmatch values ( 'basemap_landuse',
  '',
  'shop',
  '#landuse=>shop'
);

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
