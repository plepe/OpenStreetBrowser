select relation_id, assemble_multipolygon(relation_id) from relation_tags left join osm_polygon on 'rel_'||relation_id=osm_id where k='type' and v='multipolygon' and osm_id is null;
