-- As long as we don't have a better way, we match on the ref
update planet_osm_line_route set network='e-road' from planet_osm_rels where planet_osm_rels.id=planet_osm_line_route.id and planet_osm_rels.type='route' and planet_osm_rels.route='road' and planet_osm_rels.ref like 'E %';
