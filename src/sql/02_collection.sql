drop table if exists planet_osm_colls;
create table planet_osm_colls (
  id		int4		not null, -- should be def. as primary key
  type		text		not null,
  tags_k	text[]		null,
  tags_v	text[]		null
);

drop table if exists coll_tags;
create table coll_tags (
  coll_id	int4		not null,
  k		text		not null,
  v		text		not null
);

drop table if exists coll_members;
create table coll_members (
  coll_id	int4		not null,
  member_id	int4		not null,
  member_type	smallint 	not null,
  member_role	text		
);

create index planet_osm_colls_id on planet_osm_colls(id);
create index coll_tags_id on coll_tags(coll_id);
create index coll_tags_k on coll_tags(k);
create index coll_members_coll_id on coll_members(coll_id);
create index coll_members_member_id on coll_members(member_id);

-- member_type: 
