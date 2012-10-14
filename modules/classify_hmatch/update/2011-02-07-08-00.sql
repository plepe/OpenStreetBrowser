drop index classify_hmatch_idx_notkeyexists;
drop index classify_hmatch_idx_keyexists;
create index classify_hmatch_idx on classify_hmatch using btree("type");
