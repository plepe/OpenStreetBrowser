delete from classify_hmatch where type='basemap_area_text' and match @> 'natural=>mountain_range';
insert into classify_hmatch values ('basemap_area_text',
  'natural=>mountain_range',
  null,
  '#area_text=>mountain_range'
);
