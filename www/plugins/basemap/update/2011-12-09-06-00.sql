delete from classify_hmatch where type='basemap_railway_tracks';

---- if a tracks= value is set use this one for sure
insert into classify_hmatch values ( 'basemap_railway_tracks',
  'tracks=>1',
  null,
  '#railway_tracks=>single',
  30
);
insert into classify_hmatch values ( 'basemap_railway_tracks',
  'tracks=>single',
  null,
  '#railway_tracks=>single',
  30
);
insert into classify_hmatch values ( 'basemap_railway_tracks',
  'tracks=>2',
  null,
  '#railway_tracks=>double',
  30
);
insert into classify_hmatch values ( 'basemap_railway_tracks',
  'tracks=>double',
  null,
  '#railway_tracks=>double',
  30
);
insert into classify_hmatch values ( 'basemap_railway_tracks',
  'tracks=>3',
  null,
  '#railway_tracks=>multiple',
  30
);
insert into classify_hmatch values ( 'basemap_railway_tracks',
  'tracks=>4',
  null,
  '#railway_tracks=>multiple',
  30
);
insert into classify_hmatch values ( 'basemap_railway_tracks',
  'tracks=>5',
  null,
  '#railway_tracks=>multiple',
  30
);
insert into classify_hmatch values ( 'basemap_railway_tracks',
  'tracks=>6',
  null,
  '#railway_tracks=>multiple',
  30
);
---- for tram and light_rail the value left and right for tracks= are also valid
insert into classify_hmatch values ( 'basemap_railway_tracks',
  'railway=>tram, tracks=>left',
  null,
  '#railway_tracks=>left',
  29
);
insert into classify_hmatch values ( 'basemap_railway_tracks',
  'railway=>tram, tracks=>right',
  null,
  '#railway_tracks=>right',
  29
);
insert into classify_hmatch values ( 'basemap_railway_tracks',
  'railway=>light_rail, tracks=>left',
  null,
  '#railway_tracks=>left',
  29
);
insert into classify_hmatch values ( 'basemap_railway_tracks',
  'railway=>light_rail, tracks=>right',
  null,
  '#railway_tracks=>right',
  29
);
---- for other railway=* match tracks=left and =right to 'single'
insert into classify_hmatch values ( 'basemap_railway_tracks',
  'tracks=>left',
  null,
  '#railway_tracks=>single',
  28
);
insert into classify_hmatch values ( 'basemap_railway_tracks',
  'tracks=>right',
  null,
  '#railway_tracks=>single',
  28
);
---- default values for railway=tram and railway=light_rail: tracks=single, 
---- but use tracks=double if it's own an highway, but only of it's not oneway
insert into classify_hmatch values ( 'basemap_railway_tracks',
  'railway=>tram, oneway=>yes',
  'highway',
  '#railway_tracks=>single',
  19
);
insert into classify_hmatch values ( 'basemap_railway_tracks',
  'railway=>tram, oneway=>1',
  'highway',
  '#railway_tracks=>single',
  19
);
insert into classify_hmatch values ( 'basemap_railway_tracks',
  'railway=>tram, oneway=>-1',
  'highway',
  '#railway_tracks=>single',
  19
);
insert into classify_hmatch values ( 'basemap_railway_tracks',
  'railway=>tram',
  'highway',
  '#railway_tracks=>double',
  18
);
insert into classify_hmatch values ( 'basemap_railway_tracks',
  'railway=>tram',
  null,
  '#railway_tracks=>single',
  17
);
insert into classify_hmatch values ( 'basemap_railway_tracks',
  'railway=>light_rail, oneway=>yes',
  'highway',
  '#railway_tracks=>single',
  19
);
insert into classify_hmatch values ( 'basemap_railway_tracks',
  'railway=>light_rail, oneway=>1',
  'highway',
  '#railway_tracks=>single',
  19
);
insert into classify_hmatch values ( 'basemap_railway_tracks',
  'railway=>light_rail, oneway=>-1',
  'highway',
  '#railway_tracks=>single',
  19
);
insert into classify_hmatch values ( 'basemap_railway_tracks',
  'railway=>light_rail',
  'highway',
  '#railway_tracks=>double',
  18
);
insert into classify_hmatch values ( 'basemap_railway_tracks',
  'railway=>light_rail',
  null,
  '#railway_tracks=>single',
  17
);
---- other default values
insert into classify_hmatch values ( 'basemap_railway_tracks',
  'railway=>subway',
  null,
  '#railway_tracks=>double',
  19
);
insert into classify_hmatch values ( 'basemap_railway_tracks',
  '',
  'railway',
  '#railway_tracks=>single',
  9
);
