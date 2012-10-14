<?
function mem_check_get_stat() {
  $f=fopen("/proc/meminfo", "r");
  $stat=array();
  while($r=fgets($f)) {
    if(preg_match("/^(.*):[ \t]*([0-9]+) kB/", $r, $m)) {
      $stat[$m[1]]=$m[2];
    }
  }
  fclose($f);

  return $stat;
}

function mem_check_tick() {
  $stat=mem_check_get_stat();

  $free=$stat['MemFree']+$stat['Cached'];
  // print "Mem Free: $free kB\n";

  if($free<2000000) {
    print "Restarting renderd\n";
    renderd_restart();
  }
}

register_hook("mcp_tick", "mem_check_tick");
