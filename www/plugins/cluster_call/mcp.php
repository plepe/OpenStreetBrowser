<?
global $cluster_call_registered;
global $cluster_call_done;
$cluster_call_registered=array();
$cluster_call_done=array();

function cluster_call_tick() {
  global $cluster_call_done;
  global $cluster_call_registered;
  global $cluster_call_last_clean;
  global $root_path;
  // $t=microtime(true);
  $todo=array();
  $listed=array();

  // process through currently listed calls
  $res=sql_query("select * from cluster_call");
  while($elem=pg_fetch_assoc($res)) {
    // add new calls to todolist
    if(!isset($cluster_call_done[$elem['now']])) {
      $todo[$elem['now']][]=$elem;
    }

    // check which calls are currently active
    $listed[$elem['now']]=$elem['now'];
  }

  // if there's a todolist then iterate through it and call
  // registered functions
  if(sizeof($todo)) {
    foreach($todo as $now=>$now_list) {
      foreach($now_list as $do)
	if(isset($cluster_call_registered[$do['event']]))
	  foreach($cluster_call_registered[$do['event']] as $h) {
	    $h($do['parameters'], $now, $do['event']);
	  }
    }
  }

  // remember done calls
  $cluster_call_done=$listed;
  $cluster_call_file="$root_path/data/cluster_call.save";
  file_put_contents($cluster_call_file, serialize($cluster_call_done));

  // After some time delete entries in cluster_call
  if((!isset($cluster_call_last_clean))||($cluster_call_last_clean+60<time())) {
    $cluster_call_last_clean=time();
    sql_query("delete from cluster_call where now<now()-interval '6 hours'");

    print "Clean cluster calls\n";
  }

  // print "Check cluster call: ".sprintf("%.1fms", ((microtime(true)-$t)*1000))."\n";
}

function cluster_call_register($event, $fun) {
  global $cluster_call_registered;

  $cluster_call_registered[$event][]=$fun;
}

function cluster_call($event, $params=0) {
  $event=postgre_escape($event);
  $params=postgre_escape(serialize($params));

  sql_query("select cluster_call($event, $params);");
}

function cluster_call_start() {
  global $cluster_call_done;
  global $root_path;
  
  $cluster_call_file="$root_path/data/cluster_call.save";
  if(file_exists($cluster_call_file))
    $cluster_call_done=unserialize(file_get_contents("$cluster_call_file"));
}

register_hook("mcp_start", "cluster_call_start");
register_hook("mcp_tick", "cluster_call_tick");
