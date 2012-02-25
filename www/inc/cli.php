<?
if(is_array($argv)) {
  // for command line tools (in the bin/-directory) find $root_path
  $cli_dir=substr($argv[0], 0, strrpos($argv[0], "/bin/"));
  $root_path=$cli_dir;
}
