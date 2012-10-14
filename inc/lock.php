<?
function lock_dir($dir) {
  $count=0;
  while(file_exists("$dir/.lock")&&($count<5)) {
    sleep(1);
    $count++;
  }

  if(file_exists("$dir/.lock")) {
    print "Couldn't lock directory!<br>\n";
  }

  touch("$dir/.lock");
}

function unlock_dir($dir) {
  unlink("$dir/.lock");
}
