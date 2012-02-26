<?
function mcp_process($fifo) {
  $todo=trim(fgets($fifo));
  $todo=explode(" ", $todo);

  switch($todo[0]) {
    case "restart":
      call_hooks("mcp_restart");
      break;
    case "compile":
      compile($todo[1]);
      break;
    default:
      print "didn't understand ".implode(" ", $todo)."\n";
  }
  return 1;
}

umask(0);
posix_mkfifo($fifo_path, 0666);
$fifo=fopen($fifo_path, "r+");

mcp_register_stream(MCP_READ, $fifo, "mcp_process");
