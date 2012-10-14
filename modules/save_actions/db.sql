drop table if exists save_actions;
create table save_actions as (select now(), * from actions);
create index save_actions_now on save_actions(now);
