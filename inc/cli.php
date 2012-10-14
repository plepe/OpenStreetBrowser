<?
if(is_array($argv)) {
  // for command line tools (in the bin/-directory) find $root_path
  $path=$argv[0];
  if(substr($path, 0, 1)!="/")
    $path=getcwd()."/".$argv[0];

  $cli_dir=substr($path, 0, strrpos($path, "/bin/"));
  $root_path=$cli_dir;
}
