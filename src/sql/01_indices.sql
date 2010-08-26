create index node_tags_k on node_tags(k);
create index way_tags_k on way_tags(k);
create index relation_tags_k on relation_tags(k);

create index relations_members_pk on relation_members (relation_id);
create index relations_members_mem on relation_members (member_id);
create index relations_members_type on relation_members (member_type);
create index relations_members_role on relation_members (member_role);

create index way_nodes_seq_id on way_nodes("sequence_id");

drop table if exists indexes;
create table indexes (
  _table		text	not null,
  _key		text	not null,
  _type		text	not null,
  _val		text	null,
  id		text	not null,
  primary key(_table, _key, _type, _val, id)
);
