alter table classify_hmatch add column type_match    hstore          not null default ''::hstore;
