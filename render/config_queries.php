<?
$query=array();
$query['highway']= <<<EOT
	  (CASE
	    WHEN osm_tags->'highway' in ('motorway', 'motorway_link', 'trunk', 'trunk_link') THEN 'motorway'
	    WHEN osm_tags->'highway' in ('primary', 'primary_link', 'secondary', 'tertiary') THEN 'major' 
	    WHEN osm_tags->'highway' in ('unclassified', 'road', 'residential') THEN 'minor'
	    WHEN osm_tags->'highway' in ('living_street', 'pedestrian', 'byway') THEN 'pedestrian'
	    WHEN osm_tags->'highway' in ('service', 'bus_guideway', 'track') THEN 'service'
	    WHEN osm_tags->'highway' in ('path', 'cycleway', 'footway', 'bridleway', 'steps') THEN 'path'
	    WHEN osm_tags->'railway' in ('platform') THEN 'path'
	    WHEN osm_tags->'railway' in ('tram', 'light_rail', 'narrow_gauge', 'rail', 'subway', 'preserved', 'monorail') THEN 'railway'
	    WHEN osm_tags->'aeroway' in ('runway') THEN 'aeroway'
	    WHEN osm_tags->'aeroway' in ('taxiway') THEN 'aeroway'
	    WHEN osm_tags->'waterway' in ('river', 'stream', 'canal') THEN 'waterway'
	    WHEN osm_tags?'barrier' THEN 'barrier'
	    WHEN osm_tags->'natural' in ('cliff') THEN 'natural'
	    WHEN osm_tags->'power' in ('line', 'minor_line') THEN 'power'
	    WHEN osm_tags->'man_made' in ('pipeline') THEN 'pipeline'
	    ELSE osm_tags->'highway' END) as highway_type,
	  (CASE
	    /* motorway */
	    WHEN osm_tags->'highway' in ('motorway') THEN 't1'
	    WHEN osm_tags->'highway' in ('trunk') THEN 't2'
	    WHEN osm_tags->'highway' in ('motorway_link') THEN 't3'
	    WHEN osm_tags->'highway' in ('trunk_link') THEN 't4'
	    /* major */
	    WHEN osm_tags->'highway' in ('primary') THEN 't1'
	    WHEN osm_tags->'highway' in ('primary_link') THEN 't2'
	    WHEN osm_tags->'highway' in ('secondary') THEN 't3'
	    WHEN osm_tags->'highway' in ('tertiary') THEN 't4'
	    /* service */
	    WHEN osm_tags->'highway' in ('service', 'bus_guideway') THEN 't1'
	    WHEN osm_tags->'highway' in ('track') THEN 't2'
	    /* path */
	    WHEN osm_tags->'highway' in ('steps') THEN 't1'
	    /* railway */
	    WHEN osm_tags->'railway' in ('tram', 'light_rail', 'narrow_gauge') THEN 't1'
	    WHEN osm_tags->'railway' in ('rail', 'subway', 'preserved', 'monorail') THEN 't2'
	    /* aeroway */
	    WHEN osm_tags->'aeroway' in ('runway') THEN 't1'
	    WHEN osm_tags->'aeroway' in ('taxiway') THEN 't2'
	    /* water */
	    WHEN osm_tags->'waterway' in ('river') THEN 't1'
	    WHEN osm_tags->'waterway' in ('canal') THEN 't2'
	    WHEN osm_tags->'waterway' in ('stream') THEN 't3'
	    /* barrier */
	    WHEN osm_tags->'barrier' in ('wall', 'city_wall') THEN 't1'
	    /* natural */
	    WHEN osm_tags->'natural' in ('cliff') THEN 't1'
	    /* power */
	    WHEN osm_tags->'power' in ('line') and osm_tags->'importance'='international' THEN 't1'
	    WHEN osm_tags->'power' in ('line') and osm_tags->'importance'='national' THEN 't2'
	    WHEN osm_tags->'power' in ('line') and osm_tags->'importance'='regional' THEN 't3'
	    WHEN osm_tags->'power' in ('line') and osm_tags->'importance'='urban' THEN 't4'
	    WHEN osm_tags->'power' in ('line') and osm_tags->'importance'='suburban' THEN 't5'
	    WHEN osm_tags->'power' in ('line') and osm_tags->'importance'='local' THEN 't6'
	    WHEN osm_tags->'power' in ('minor_line') THEN 't6'
	    /* pipeline */
	    WHEN osm_tags->'man_made'='pipeline' and osm_tags->'type' in ('water') THEN 't1'
	    WHEN osm_tags->'man_made'='pipeline' and osm_tags->'type' in ('oil') THEN 't2'
	    WHEN osm_tags->'man_made'='pipeline' and osm_tags->'type' in ('gas') THEN 't3'
	    WHEN osm_tags->'man_made'='pipeline' and osm_tags->'type' in ('sewage') THEN 't4'
	    WHEN osm_tags->'man_made'='pipeline' and osm_tags->'type' in ('heat', 'hot_water') THEN 't5'
	    /* ELSE */
	    ELSE 'default'
	    END) as highway_sub_type,
  (CASE
    WHEN osm_tags->'highway'='pedestrian' THEN 'pedestrian'
    WHEN osm_tags->'amenity'='parking' THEN 'parking'
  END) as highway_poly_type
EOT;
$query['landuse']=<<<EOT
       (CASE
         WHEN osm_tags->'leisure' in ('park')
	   OR osm_tags->'landuse' in ('village_green', 'recreation_ground', 'grass')
	   THEN 'park'
	 WHEN osm_tags->'leisure' in ('golf_course', 'playground', 'sports_centre', 'track',
	                    'pitch', 'water_park', 'piste')
	   THEN 'sport'
	 WHEN osm_tags->'leisure' in ('nature_reserve')
	   THEN 'nature_reserve'
	 WHEN osm_tags->'natural' in ('wood', 'wetland', 'marsh', 'glacier', 'scree', 'scrub', 'heath')
	   THEN 'natural0'
	 WHEN osm_tags->'natural' in ('mud', 'beach', 'cliff', 'rock')
	   THEN 'natural1'
         WHEN osm_tags->'landuse' in ('cemetery')
	   THEN 'cemetery'
	 WHEN osm_tags->'landuse' in ('forest', 'wood')
	   THEN 'natural0'
	 WHEN osm_tags->'leisure' in ('common', 'garden')
	   OR osm_tags->'landuse' in ('meadow', 'farm', 'greenhouse_horticulture', 'farmyard', 'farmland', 'vineyard', 'orchard')
	   OR osm_tags->'natural' in ('fell', 'meadow')
	   THEN 'garden'
	 WHEN osm_tags->'landuse' in ('school')
	   THEN 'education'
	 WHEN osm_tags->'landuse' in ('quarry', 'landfill', 'brownfield', 
	                    'railway', 'construction', 'military', 'industrial')
	   OR osm_tags->'amenity' in ('bus_station')
	   OR osm_tags->'aeroway' in ('aerodrome', 'apron')
	   OR osm_tags->'military' in ('barracks', 'airfield')
	   OR osm_tags->'power' in ('generator', 'station', 'sub_station')
	   THEN 'industrial'
	 WHEN osm_tags->'landuse' in ('residential', 'allotments')
	   THEN 'residential'
	 WHEN osm_tags?'historic'
	   THEN 'historic'
	 WHEN osm_tags?'tourism'
	   THEN 'tourism'
	 WHEN (not osm_tags?'building' OR osm_tags->'building'='no') THEN (CASE
	   WHEN osm_tags->'amenity' in ('college', 'cinema', 'kindergarten', 'library', 'school', 'theatre', 'arts_centre', 'university')
	     THEN 'education'
	    WHEN osm_tags->'amenity' in ('hospital', 'emergency_phone', 'fire_station', 'police')
	      THEN 'emergency'
	    WHEN osm_tags->'amenity' in ('pharmacy', 'baby_hatch', 'dentist', 'doctors', 'veterinary')
	      THEN 'health'
	    WHEN osm_tags->'amenity' in ('government', 'gouvernment', 'public_building', 'court_house', 'embassy', 'prison', 'townhall')
	      THEN 'public'
	    WHEN osm_tags->'amenity' in ('marketplace') THEN 'shop'
	    WHEN osm_tags?'shop' THEN 'shop'
	    END)
	END) as landuse,
	(CASE 
	 WHEN osm_tags->'leisure' in ('golf_course', 'playground', 'sports_centre', 'track',
	                    'pitch', 'water_park', 'piste') THEN
	    (CASE
	      WHEN osm_tags->'landuse' in ('piste') THEN 't1'
	      ELSE 't0'
	      END)
	  WHEN osm_tags->'natural' in ('wood', 'wetland', 'marsh', 'glacier', 'scree', 'scrub', 'heath', 'mud', 'beach') THEN
	    (CASE
	      WHEN osm_tags->'natural' in ('wood', 'scrub') THEN 't0'
	      WHEN osm_tags->'natural' in ('wetland', 'marsh') THEN 't1'
	      WHEN osm_tags->'natural' in ('glacier') THEN 't2'
	      WHEN osm_tags->'natural' in ('scree', 'heath') THEN 't3'
	      END)
	  WHEN osm_tags->'natural' in ('mud', 'beach', 'cliff', 'rock') THEN
	    (CASE
	      WHEN osm_tags->'natural' in ('mud') THEN 't0'
	      WHEN osm_tags->'natural' in ('beach') THEN 't1'
	      WHEN osm_tags->'natural' in ('cliff', 'rock') THEN 't2'
	      END)
	  WHEN osm_tags->'landuse' in ('forest', 'wood') THEN 't0'
 	  WHEN osm_tags->'landuse' in ('quarry', 'farmyard', 'farmland', 'landfill', 'brownfield', 
	                    'railway', 'construction', 'military', 'industrial')
	   OR osm_tags->'amenity' in ('bus_station')
	   OR osm_tags->'aeroway' in ('aerodrome', 'apron')
	   OR osm_tags->'military' in ('barracks', 'airfield')
	   OR osm_tags->'power' in ('station', 'sub_station') THEN
	     (CASE
	       WHEN osm_tags->'landuse' in ('military')
	         OR osm_tags->'military' in ('barracks', 'airfield') THEN 't1'
	       ELSE 't0'
	     END)
	END) as landuse_sub_type
EOT;
$query['base_amenity']=<<<EOT
           (CASE
	     WHEN osm_tags->'natural' in ('peak', 'volcano', 'cliff', 'cave_entrance') THEN 'natural_big'
	     WHEN osm_tags?'natural' THEN 'natural'

	     WHEN osm_tags->'highway' in ('mini_roundabout', 'gate', 'mountain_pass') THEN 'transport'
	     WHEN osm_tags->'railway' in ('level_crossing') THEN 'transport'
	     WHEN osm_tags->'amenity' in ('fountain') THEN 'obstacle'
	     WHEN osm_tags->'historic' in ('monument', 'memorial') THEN 'obstacle'
	     WHEN osm_tags?'power' THEN 'power'
	   END) as amenity_type,
	   (CASE
	     /* type = natural_big and natural */
	     WHEN osm_tags?'natural' THEN (CASE
	       WHEN osm_tags->'natural' in ('peak') THEN 't1'
	       WHEN osm_tags->'natural' in ('cliff') THEN 't2'
	       WHEN osm_tags->'natural' in ('cave_entrance') THEN 't3'
	       WHEN osm_tags->'natural' in ('land') THEN 't4'
	       WHEN osm_tags->'natural' in ('volcano') THEN 't5'

	       WHEN osm_tags->'natural' in ('spring') THEN 't1'
	       WHEN osm_tags->'natural' in ('beach') THEN 't2'
	       WHEN osm_tags->'natural' in ('tree') THEN 't4'
	     END)

	     /* type = transport */
	     WHEN osm_tags->'railway' in ('level_crossing') THEN 't1'
	     WHEN osm_tags->'highway' in ('mini_roundabout') THEN 't2'
             WHEN osm_tags->'highway' in ('gate') THEN 't3'
             WHEN osm_tags->'highway' in ('mountain_pass') THEN 't4'

	     /* type = obstacle */
             WHEN osm_tags->'amenity' in ('fountain') THEN 't1'
             WHEN osm_tags->'historic' in ('monument', 'memorial') THEN 't2'

	     /* type = power */
	     WHEN osm_tags->'power' in ('tower') THEN 't1'
	     WHEN osm_tags->'power' in ('station', 'sub_station', 'generator') THEN 't2'

	   END) as amenity_sub_type,
	   (CASE
	     WHEN osm_tags->'natural' in ('peak', 'volcano', 'glacier') THEN osm_tags->'ele'
	     WHEN osm_tags->'highway' in ('mountain_pass') THEN osm_tags->'ele'
	   END) as amenity_desc
EOT;
$query['places']=<<<EOT
      (select 'node' as type, id_place_node as id, name, way,
       (CASE 
         WHEN osm_tags->'place'='city' AND osm_tags->'population'>=1000000 THEN 'city_large'
	 WHEN osm_tags->'place'='city' AND osm_tags->'population'>=200000 THEN 'city_medium'
	 WHEN osm_tags->'place'='town' AND osm_tags->'population'>=30000 THEN 'town_large'
	 ELSE osm_tags->'place'
       END) as place,
       osm_tags->'label' from planet_osm_place) as places
EOT;
$query['places_sort']=<<<EOT
     (CASE
       WHEN osm_tags->'place'='continent' THEN 0
       WHEN osm_tags->'place'='country'   THEN 1
       WHEN osm_tags->'place'='state'     THEN 2
       WHEN osm_tags->'place'='city'      THEN 3
       WHEN osm_tags->'place'='region'    THEN 4
       WHEN osm_tags->'place'='island'    THEN 5
       WHEN osm_tags->'place'='town'      THEN 6
       WHEN osm_tags->'place'='village'   THEN 7
       WHEN osm_tags->'place'='hamlet'    THEN 8
       WHEN osm_tags->'place'='suburb'    THEN 9
       WHEN osm_tags->'place'='locality'  THEN 10
       WHEN osm_tags->'place'='islet'     THEN 11
       WHEN osm_tags->'place'='isolated_dwelling'     THEN 12
       ELSE                          20
     END) ASC,
     (CASE
       WHEN not osm_tags?'population' THEN 0
       ELSE parse_number(osm_tags->'population')
     END) DESC
EOT;
$query['shop']=<<<EOT
(CASE 
  WHEN osm_tags->'shop' in ('supermarket', 'groceries', 'grocery') THEN 'supermarket'
  WHEN osm_tags->'shop' in ('supermarket', 'groceries', 'grocery') THEN 'health'
  WHEN osm_tags->'amenity' in ('pharmacy') THEN 'health'
  WHEN osm_tags->'amenity'='vending_machine' THEN 'vending'
  WHEN osm_tags->'amenity'='marketplace' THEN 'marketplace'
  WHEN osm_tags?'shop' THEN 'other'
END) as shop_type,
(CASE
  WHEN not osm_tags?'shop' and osm_tags->'amenity' in ('pharmacy') THEN 't1'
END) as shop_sub_type,
(CASE
  WHEN not osm_tags?'shop' and 
    osm_tags->'amenity' in ('pharmacy') THEN osm_tags->'amenity'
  WHEN osm_tags->'amenity'='vending_machine' THEN osm_tags->'vending'
  WHEN osm_tags->'amenity'='marketplace' THEN 'marketplace'
  ELSE osm_tags->'shop'
END) as shop_desc,
(CASE
  WHEN osm_tags->'network' in ('international', 'national') THEN 'national'
  WHEN osm_tags->'network' in ('region', 'urban', 'local') THEN osm_tags->'network'
  WHEN osm_tags->'shop' in ('mall', 'shopping_center', 'shopping_centre') THEN 'region'
  WHEN osm_tags->'shop' in ('supermarket', 'department_store', 'market') THEN 'urban'
  WHEN osm_tags->'amenity' in ('marketplace') THEN 'urban'
  ELSE 'local'
END) as shop_network
EOT;
$query['highway_level']=<<<EOT
(CASE 
  WHEN osm_tags->'highway' in ('motorway', 'motorway_link') THEN 21
  WHEN osm_tags->'highway' in ('trunk', 'trunk_link') THEN 20
  WHEN osm_tags->'highway' in ('primary', 'primary_link') THEN 12
  WHEN osm_tags->'highway' in ('secondary') THEN 11
  WHEN osm_tags->'highway' in ('tertiary') THEN 10
  WHEN osm_tags->'highway' in ('unclassified', 'road', 'residential') THEN 4
  WHEN osm_tags->'highway' in ('living_street', 'service', 'pedestrian', 'steps', 'bus_guideway', 'byway') THEN 3
  WHEN osm_tags->'highway' in ('track', 'path', 'cycleway', 'footway', 'bridleway', 'ford') THEN 2
  WHEN osm_tags->'railway' in ('platform') THEN 2
  WHEN osm_tags->'railway' in ('tram', 'rail', 'narrow_gauge', 'light_rail') THEN 1
  WHEN osm_tags?'barrier' THEN 0
  WHEN osm_tags?'power' THEN 0
  END)
EOT;
$query['power']=<<<EOT
osm_tags->'power' as power_type
EOT;
$query['bridge_tunnel']=<<<EOT
  (CASE 
    WHEN osm_tags->'bridge' in ('yes', 'true', '1', 'viaduct', 'swing', 'aqueduct') THEN 'yes' 
    ELSE 'no'
  END) as bridge,
  (CASE 
    WHEN osm_tags->'tunnel' in ('yes', 'true', '1') THEN 'yes' 
    ELSE 'no'
  END) as tunnel
EOT;
$query['water_area']=<<<EOT
  (CASE
    WHEN osm_tags?'waterway' THEN osm_tags->'waterway'
    WHEN osm_tags?'landuse' THEN osm_tags->'landuse'
    WHEN osm_tags?'natural' THEN osm_tags->'natural'
  END)
EOT;
$query['rail']=<<<EOT
  (CASE 
    WHEN osm_tags->'railway' in ('tram', 'light_rail') THEN 'tram'
    WHEN osm_tags->'railway' in ('rail', 'narrow_gauge', 'monorail', 'subway') THEN 'rail' 
    END) as "railway",
  (CASE WHEN osm_tags->'railway' in ('subway', 'tram', 'light_rail') THEN
    (CASE
      WHEN osm_tags->'tracks' in ('left', 'right') THEN osm_tags->'tracks'
      WHEN osm_tags->'tracks' in ('1', 'single') THEN 'single'
      WHEN osm_tags->'tracks' in ('3', '4', '5', '6') THEN  'multiple'
      ELSE 'double' END) 
  ELSE
    (CASE
      WHEN osm_tags->'tracks' in ('2', 'double') THEN 'double'
      WHEN osm_tags->'tracks' in ('3', '4', '5', '6') THEN  'multiple'
      ELSE 'single' END) END) as "tracks"
EOT;
$query['buildings']=<<<EOT
 (CASE
    WHEN osm_tags->'building' in ('no')
      THEN null
    WHEN osm_tags->'amenity' in ('place_of_worship')
      THEN 'worship'
    WHEN osm_tags->'highway' in ('toll_booth')
      OR osm_tags->'railway' in ('station', 'platform')
      OR osm_tags->'aeroway' in ('terminal', 'helipad')
      OR osm_tags->'aerialway' in ('station')
      OR osm_tags->'amenity' in ('ferry_terminal')
      THEN 'road_amenities'
    WHEN osm_tags->'barrier' in ('hedge', 'fence')
      THEN 'nature_building'
    WHEN osm_tags->'power' in ('generator')
      OR osm_tags->'man_made' in ('gasometer', 'wasterwater_plant', 'watermill', 'water_tower', 'water_works', 'windmill', 'works', 'reservoir_covered')
      THEN 'industrial'
    WHEN osm_tags->'amenity' in ('college', 'cinema', 'kindergarten', 'library', 'school', 'university')
      THEN 'education'
    WHEN osm_tags->'amenity' in ('theatre', 'arts_centre', 'cinema', 'fountain', 'studio')
      THEN 'culture'
    WHEN osm_tags?'shop'
      THEN 'shop'
    WHEN osm_tags->'amenity' in ('hospital', 'emergency_phone', 'fire_station', 'police')
      THEN 'emergency'
    WHEN osm_tags->'amenity' in ('pharmacy', 'baby_hatch', 'dentist', 'doctors', 'veterinary')
      THEN 'health'
    WHEN osm_tags->'amenity' in ('government', 'gouvernment', 'public_building', 'court_house', 'embassy', 'prison', 'townhall')
      THEN 'public'
    WHEN osm_tags->'amenity' in ('post_office')
      THEN 'communication'
    WHEN osm_tags->'amenity' in ('hospital', 'baby_hatch', 'dentist', 'doctors', 'pharmacy', 'veterinary')
      THEN 'public'
    WHEN osm_tags->'tourism' in ('museum', 'artwork', 'attraction', 'viewpoint', 'theme_park', 'zoo')
      THEN 'culture'
    WHEN osm_tags?'military'
      THEN 'military'
    WHEN osm_tags?'historic'
      THEN 'historic'
    WHEN osm_tags->'building' in ('residental', 'residential', 'apartments', 'block', 'flats', 'appartments')
       THEN 'residential'
    WHEN osm_tags->'amenity' in ('bicycle_parking', 'bicycle_rental', 'shelter')
      OR osm_tags->'leisure' in ('sports_centre', 'stadium', 'track', 'pitch', 'ice_rink')
      OR osm_tags?'sport'
      THEN 'sport'
    ELSE
      'default'
  END) as "building"
EOT;
$query['place']=<<<EOT
  (CASE 
    WHEN osm_tags->'place'='country' AND parse_number_or_0(osm_tags->'population')>20000000 THEN 'country_large'
    WHEN osm_tags->'place'='country' AND parse_number_or_0(osm_tags->'population')>1000000 THEN 'country_medium'
    WHEN osm_tags->'place'='city' AND parse_number_or_0(osm_tags->'population')>1000000 THEN 'city_large'
    WHEN osm_tags->'place'='city' AND parse_number_or_0(osm_tags->'population')>200000 THEN 'city_medium'
    WHEN osm_tags->'place'='town' AND parse_number_or_0(osm_tags->'population')>30000 THEN 'town_large'
    ELSE osm_tags->'place'
  END) as "place"
EOT;
