<?
function state_info_update($params, $now, $event) {
  print "STATE_INFO_UPDATE! $now\n";
}

cluster_call_register("update_db", "state_info_update");
