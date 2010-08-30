drop table if exists osm_update;
create table osm_update (
  update_date		date		not null,
  begin_download	timestamp	null,
  end_download		timestamp	null,
  log_download		text		null,
  exit_download		int		null,
  begin_change		timestamp	null,
  end_change		timestamp	null,
  log_change		text		null,
  exit_change		int		null,
  primary key(update_date)
);
