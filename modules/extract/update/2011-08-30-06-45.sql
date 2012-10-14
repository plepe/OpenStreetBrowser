insert into osm_point_extract ( 
 select * 
 from ( 
   select 
     osm_id, 
     osm_tags, 
     osm_way 
   from 
     osm_point 
   where 
     (osm_tags @> 'place=>ocean' or osm_tags @> 'place=>sea')
 ) x 
 where 
   extract_classify_point(osm_id, osm_tags, osm_way));;


