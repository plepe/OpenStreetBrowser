update planet_osm_point set "highway"='mountain_pass' from node_tags where osm_id=node_id and k='mountain_pass' and v='yes';
update planet_osm_line set "tunnel"='yes' from way_tags where "man_made"='pipeline' and osm_id=way_id and k='location' and v in ('underground', 'underwater');
update planet_osm_line set "voltage"=v from way_tags where "man_made"='pipeline' and osm_id=way_id and k='type';
