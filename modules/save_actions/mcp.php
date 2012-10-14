<?
function save_action_clean_up_master() {
  global $db_central;

  print "Clean-Up save_actions\n";
  sql_query("delete from save_actions where now<now()-interval '6 hours'");
}

register_hook("mcp_clean_up_master", "save_action_clean_up_master");
