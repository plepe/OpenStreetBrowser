drop aggregate if exists to_textarray(text);
CREATE AGGREGATE to_textarray (
BASETYPE = text,
SFUNC = array_append,
STYPE = text[],
INITCOND = '{}'); 

drop aggregate if exists to_intarray(int4);
CREATE AGGREGATE to_intarray (
BASETYPE = int4,
SFUNC = array_append,
STYPE = int4[],
INITCOND = '{}'); 

drop aggregate if exists to_intarray(bigint);
CREATE AGGREGATE to_intarray (
BASETYPE = bigint,
SFUNC = array_append,
STYPE = bigint[],
INITCOND = '{}'); 

drop aggregate if exists to_array(hstore);
CREATE AGGREGATE to_array (
BASETYPE = hstore,
SFUNC = array_append,
STYPE = hstore[],
INITCOND = '{}'); 

drop aggregate if exists to_intarray(int[]);
CREATE AGGREGATE to_intarray (
BASETYPE = int4[],
SFUNC = array_cat,
STYPE = int4[],
INITCOND = '{}'); 

drop aggregate if exists to_array(geometry);
CREATE AGGREGATE to_array (
BASETYPE = geometry,
SFUNC = array_append,
STYPE = geometry[],
INITCOND = '{}');
