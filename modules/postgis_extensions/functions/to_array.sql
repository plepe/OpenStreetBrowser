drop aggregate if exists to_array(geometry);
CREATE AGGREGATE to_array (
BASETYPE = geometry,
SFUNC = array_append,
STYPE = geometry[],
INITCOND = '{}'); 
