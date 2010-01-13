<?
$query=array();
$query["highway"]= <<<EOT
	  (CASE
	    WHEN "highway" in ('motorway', 'motorway_link', 'trunk', 'trunk_link') THEN 'motorway'
	    WHEN "highway" in ('primary', 'primary_link', 'secondary', 'tertiary') THEN 'major' 
	    WHEN "highway" in ('unclassified', 'road', 'residential') THEN 'minor'
	    WHEN "highway" in ('living_street', 'pedestrian', 'byway') THEN 'pedestrian'
	    WHEN "highway" in ('service', 'bus_guideway', 'track') THEN 'service'
	    WHEN "highway" in ('path', 'cycleway', 'footway', 'bridleway', 'steps') THEN 'path'
	    WHEN "railway" in ('platform') THEN 'path'
	    WHEN "railway" in ('tram', 'light_rail', 'narrow_gauge', 'rail', 'subway', 'preserved', 'monorail') THEN 'railway'
	    WHEN "aeroway" in ('runway') THEN 'aeroway'
	    WHEN "aeroway" in ('taxiway') THEN 'aeroway'
	    WHEN "waterway" in ('river', 'stream', 'canal') THEN 'waterway'
	    WHEN "barrier" is not null THEN 'barrier'
	    WHEN "natural" in ('cliff') THEN 'natural'
	    WHEN "power" in ('line', 'minor_line') THEN 'power'
	    WHEN "man_made" in ('pipeline') THEN 'pipeline'
	    ELSE "highway" END) as highway_type,
	  (CASE
	    /* motorway */
	    WHEN "highway" in ('motorway') THEN 't1'
	    WHEN "highway" in ('trunk') THEN 't2'
	    WHEN "highway" in ('motorway_link') THEN 't3'
	    WHEN "highway" in ('trunk_link') THEN 't4'
	    /* major */
	    WHEN "highway" in ('primary') THEN 't1'
	    WHEN "highway" in ('primary_link') THEN 't2'
	    WHEN "highway" in ('secondary') THEN 't3'
	    WHEN "highway" in ('tertiary') THEN 't4'
	    /* service */
	    WHEN "highway" in ('service', 'bus_guideway') THEN 't1'
	    WHEN "highway" in ('track') THEN 't2'
	    /* path */
	    WHEN "highway" in ('steps') THEN 't1'
	    /* railway */
	    WHEN "railway" in ('tram', 'light_rail', 'narrow_gauge') THEN 't1'
	    WHEN "railway" in ('rail', 'subway', 'preserved', 'monorail') THEN 't2'
	    /* aeroway */
	    WHEN "aeroway" in ('runway') THEN 't1'
	    WHEN "aeroway" in ('taxiway') THEN 't2'
	    /* water */
	    WHEN "waterway" in ('river') THEN 't1'
	    WHEN "waterway" in ('canal') THEN 't2'
	    WHEN "waterway" in ('stream') THEN 't3'
	    /* barrier */
	    WHEN "barrier" in ('wall', 'city_wall') THEN 't1'
	    /* natural */
	    WHEN "natural" in ('cliff') THEN 't1'
	    /* power */
	    WHEN "power" in ('line') and "importance"='international' THEN 't1'
	    WHEN "power" in ('line') and "importance"='national' THEN 't2'
	    WHEN "power" in ('line') and "importance"='regional' THEN 't3'
	    WHEN "power" in ('line') and "importance"='urban' THEN 't4'
	    WHEN "power" in ('line') and "importance"='suburban' THEN 't5'
	    WHEN "power" in ('line') and "importance"='local' THEN 't6'
	    WHEN "power" in ('minor_line') THEN 't6'
	    /* pipeline */
	    /* column voltage  holds value of tag 'type' */
	    WHEN "man_made"='pipeline' and "voltage" in ('water') THEN 't1'
	    WHEN "man_made"='pipeline' and "voltage" in ('oil') THEN 't2'
	    WHEN "man_made"='pipeline' and "voltage" in ('gas') THEN 't3'
	    WHEN "man_made"='pipeline' and "voltage" in ('sewage') THEN 't4'
	    WHEN "man_made"='pipeline' and "voltage" in ('heat', 'hot_water') THEN 't5'
	    /* ELSE */
	    ELSE 'default'
	    END) as sub_type
EOT;
$query["landuse"]=<<<EOT
       (CASE
         WHEN "leisure" in ('park')
	   OR "landuse" in ('village_green', 'recreation_ground', 'grass')
	   THEN 'park'
	 WHEN "leisure" in ('golf_course', 'playground', 'sports_centre', 'track',
	                    'pitch', 'water_park')
	   THEN 'sport'
	 WHEN "leisure" in ('nature_reserve')
	   THEN 'nature_reserve'
	 WHEN "natural" in ('wood', 'wetland', 'marsh', 'glacier', 'scree', 'scrub', 'heath', 'mud', 'beach')
	   THEN 'natural'
         WHEN "landuse" in ('cemetery')
	   THEN 'cemetery'
	 WHEN "landuse" in ('forest')
	   THEN 'natural'
	 WHEN "leisure" in ('common', 'garden')
	   OR "landuse" in ('meadow', 'farm', 'farmyard', 'farmland', 'vineyard', 'orchard')
	   OR "natural" in ('fell')
	   THEN 'garden'
	 WHEN "landuse" in ('school')
	   THEN 'education'
	 WHEN "landuse" in ('quarry', 'landfill', 'brownfield', 
	                    'railway', 'construction', 'military', 'industrial')
	   OR "amenity" in ('bus_station')
	   OR "aeroway" in ('aerodrome', 'apron')
	   OR "military" in ('barracks', 'airfield')
	   OR "power" in ('generator', 'station', 'sub_station')
	   THEN 'industrial'
	 WHEN "landuse" in ('residential', 'allotments')
	   THEN 'residential'
	 WHEN "historic" is not null
	   THEN 'historic'
	 WHEN "tourism" is not null
	   THEN 'tourism'
	 WHEN ("building" is null OR "building"='no') THEN (CASE
	   WHEN "amenity" in ('college', 'cinema', 'kindergarten', 'library', 'school', 'theatre', 'arts_centre', 'university')
	     THEN 'education'
	    WHEN "amenity" in ('hospital', 'emergency_phone', 'fire_station', 'police')
	      THEN 'emergency'
	    WHEN "amenity" in ('pharmacy', 'baby_hatch', 'dentist', 'doctors', 'veterinary')
	      THEN 'health'
	    WHEN "amenity" in ('government', 'gouvernment', 'public_building', 'court_house', 'embassy', 'prison', 'townhall')
	      THEN 'public'
	    WHEN "amenity" in ('marketplace') THEN 'shop'
	    WHEN "shop" is not null THEN 'shop'
	    END)
	END) as landuse,
	(CASE 
	  WHEN "natural" in ('wood', 'wetland', 'marsh', 'glacier', 'scree', 'scrub', 'heath', 'mud', 'beach') THEN
	    (CASE
	      WHEN "natural" in ('wood', 'scrub') THEN 't0'
	      WHEN "natural" in ('wetland', 'marsh') THEN 't1'
	      WHEN "natural" in ('glacier') THEN 't2'
	      WHEN "natural" in ('scree', 'heath') THEN 't3'
	      WHEN "natural" in ('mud') THEN 't4'
	      WHEN "natural" in ('beach') THEN 't5'
	      END)
	  WHEN "landuse" in ('forest') THEN 't0'
 	  WHEN "landuse" in ('quarry', 'farmyard', 'farmland', 'landfill', 'brownfield', 
	                    'railway', 'construction', 'military', 'industrial')
	   OR "amenity" in ('bus_station')
	   OR "aeroway" in ('aerodrome', 'apron')
	   OR "military" in ('barracks', 'airfield')
	   OR "power" in ('station', 'sub_station') THEN
	     (CASE
	       WHEN "landuse" in ('military')
	         OR "military" in ('barracks', 'airfield') THEN 't1'
	       ELSE 't0'
	     END)
	END) as sub_type
EOT;
$query["base_amenity"]=<<<EOT
           (CASE
	     WHEN "natural" in ('peak', 'volcano', 'cliff', 'cave_entrance') THEN 'natural_big'
	     WHEN "natural" is not null THEN 'natural'

	     WHEN "highway" in ('mini_roundabout', 'gate', 'mountain_pass') THEN 'transport'
	     WHEN "railway" in ('level_crossing') THEN 'transport'
	     WHEN "amenity" in ('fountain') THEN 'obstacle'
	     WHEN "historic" in ('monument', 'memorial') THEN 'obstacle'
	     WHEN "power" is not null THEN 'power'
	   END) as type,
	   (CASE
	     /* type = natural_big and natural */
	     WHEN "natural" is not null THEN (CASE
	       WHEN "natural" in ('peak', 'volcano') THEN 't1'
	       WHEN "natural" in ('cliff') THEN 't2'
	       WHEN "natural" in ('cave_entrance') THEN 't3'
	       WHEN "natural" in ('land') THEN 't4'

	       WHEN "natural" in ('spring') THEN 't1'
	       WHEN "natural" in ('beach') THEN 't2'
	       WHEN "natural" in ('tree') THEN 't4'
	     END)

	     /* type = transport */
	     WHEN "railway" in ('level_crossing') THEN 't1'
	     WHEN "highway" in ('mini_roundabout') THEN 't2'
             WHEN "highway" in ('gate') THEN 't3'
             WHEN "highway" in ('mountain_pass') THEN 't4'

	     /* type = obstacle */
             WHEN "amenity" in ('fountain') THEN 't1'
             WHEN "historic" in ('monument', 'memorial') THEN 't2'

	     /* type = power */
	     WHEN "power" in ('tower') THEN 't1'
	     WHEN "power" in ('station', 'sub_station', 'generator') THEN 't2'

	   END) as sub_type,
	   (CASE
	     WHEN "natural" in ('peak', 'volcano', 'glacier') THEN "ele"
	     WHEN "highway" in ('mountain_pass') THEN "ele"
	   END) as desc
EOT;
$query["places"]=<<<EOT
      (select 'node' as type, id_place_node as id, name, way,
       (CASE 
         WHEN "place"='city' AND "population">=1000000 THEN 'city_large'
	 WHEN "place"='city' AND "population">=200000 THEN 'city_medium'
	 WHEN "place"='town' AND "population">=30000 THEN 'town_large'
	 ELSE "place"
       END) as place,
       "label" from planet_osm_place) as places
EOT;
$query["shop"]=<<<EOT
(CASE 
  WHEN "shop" in ('supermarket', 'groceries', 'grocery') THEN 'supermarket'
  WHEN "shop" in ('supermarket', 'groceries', 'grocery') THEN 'health'
  WHEN "amenity" in ('pharmacy') THEN 'health'
  WHEN "amenity"='vending_machine' THEN 'vending'
  WHEN "amenity"='marketplace' THEN 'marketplace'
  WHEN "shop" is not null THEN 'other'
END) as shop_type,
(CASE
  WHEN "shop" is null and "amenity" in ('pharmacy') THEN 't1'
END) as shop_sub_type,
(CASE
  WHEN "shop" is null and 
    "amenity" in ('pharmacy') THEN "amenity"
  WHEN "amenity"='vending_machine' THEN "vending"
  WHEN "amenity"='marketplace' THEN 'marketplace'
  ELSE "shop"
END) as shop_desc,
(CASE
  WHEN "network" in ('international', 'national') THEN 'national'
  WHEN "network" in ('region', 'urban', 'local') THEN "network"
  WHEN "shop" in ('mall', 'shopping_center', 'shopping_centre') THEN 'region'
  WHEN "shop" in ('supermarket', 'department_store', 'market') THEN 'urban'
  WHEN "amenity" in ('marketplace') THEN 'urban'
  ELSE 'local'
END) as shop_network
EOT;
$query["highway_level"]=<<<EOT
(CASE 
  WHEN "highway" in ('motorway', 'motorway_link') THEN 21
  WHEN "highway" in ('trunk', 'trunk_link') THEN 20
  WHEN "highway" in ('primary', 'primary_link') THEN 12
  WHEN "highway" in ('secondary') THEN 11
  WHEN "highway" in ('tertiary') THEN 10
  WHEN "highway" in ('unclassified', 'road', 'residential') THEN 4
  WHEN "highway" in ('living_street', 'service', 'pedestrian', 'steps', 'bus_guideway', 'byway') THEN 3
  WHEN "highway" in ('track', 'path', 'cycleway', 'footway', 'bridleway', 'ford') THEN 2
  WHEN "railway" in ('platform') THEN 2
  WHEN "railway" in ('tram', 'rail', 'narrow_gauge', 'light_rail') THEN 1
  WHEN "barrier" is not null THEN 0
  WHEN "power" is not null THEN 0
  END)
EOT;
$query["power"]=<<<EOT
"power" as power_type
EOT;
$query["bridge_tunnel"]=<<<EOT
  (CASE 
    WHEN "bridge" in ('yes', 'true', '1', 'viaduct', 'swing', 'aqueduct') THEN 'yes' 
    ELSE 'no'
  END) as bridge,
  (CASE 
    WHEN "tunnel" in ('yes', 'true', '1') THEN 'yes' 
    ELSE 'no'
  END) as tunnel
EOT;
