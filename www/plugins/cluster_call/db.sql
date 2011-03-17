create table cluster_call (
  now		timestamp with time zone	not null,
  event		text				not null,
  parameters	text				null,
  primary key(now, event)
);
