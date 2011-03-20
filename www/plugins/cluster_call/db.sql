drop table if exists cluster_call;
create table cluster_call (
  now		timestamp with time zone	not null,
  event		text				not null,
  parameters	text				null,
  primary key(now, event)
);

create table cluster_call_registered (
  event		text				not null,
  fun		text				not null,
  primary key(event, fun)
);
create index cluster_call_registered_event on cluster_call_registered(event);
