<?
function pg_cache_clean_up_master() {
  print "call cache_clean()\n";
  sql_query("select cache_clean()");
}

register_hook("mcp_clean_up_master", "pg_cache_clean_up_master");
