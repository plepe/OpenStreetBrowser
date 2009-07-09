update planet_osm_line set "importance"=(CASE
    WHEN cast("voltage" as int)>=150000 THEN 'national'
    WHEN cast("voltage" as int)>=15000 THEN 'regional'
    WHEN cast("voltage" as int)>=1500 THEN 'urban'
    ELSE 'suburban' END)
  where "power"='line' and "voltage" similar to '^[0-9]+$';
update planet_osm_line set "importance"=(CASE
    WHEN cast(substr("voltage", 1, length("voltage")-3) as int)>=150 THEN 'national'
    WHEN cast(substr("voltage", 1, length("voltage")-3) as int)>=15 THEN 'regional'
    WHEN cast(substr("voltage", 1, length("voltage")-3) as int)>=1 THEN 'urban'
    ELSE 'suburban' END)
  where "power"='line' and "voltage" similar to '^[0-9]+ kV$';
update planet_osm_line set "importance"='suburban'
  where "power"='line' and "importance" is null;
update planet_osm_point set "importance"=l."importance" from way_nodes, planet_osm_line l where planet_osm_point.osm_id=way_nodes.node_id and l.osm_id=way_nodes.way_id and planet_osm_point."power" in ('tower', 'pole');

