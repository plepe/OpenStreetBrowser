<?
function url_historyjs_download_library() {
  global $root_path;

  if(!file_exists("$root_path/www/lib/history.js")) {
    @mkdir("$root_path/www/lib");
    system("git clone https://github.com/balupton/history.js.git $root_path/www/lib/history.js");
    debug("downloaded history.js git repo", "url_historyjs", D_WARNING);
  }
}

register_hook("mcp_start", "url_historyjs_download_library");
