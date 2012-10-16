<?
global $creole_engine;

function creole_init() {
  global $creole_engine;

  if($creole_engine)
    return;

  global $root_path;

  if((!file_exists("$root_path/lib/simplewiki/"))||
     (!file_exists("$root_path/lib/simplewiki/simplewiki.php"))) {
    debug("Library 'Simplewiki' not found! Please install.", "creole", D_ERROR);
    return;
  }

  include "$root_path/lib/simplewiki/muster_simplewiki_docnode.php";
  include "$root_path/lib/simplewiki/simplewiki_docnode.php";
  include "$root_path/lib/simplewiki/muster_simplewiki_parser.php";
  include "$root_path/lib/simplewiki/simplewiki_parser.php";
  include "$root_path/lib/simplewiki/muster_simplewiki_emitter.php";
  include "$root_path/lib/simplewiki/simplewiki_emitter.php";
  include "$root_path/lib/simplewiki/muster_simplewiki.php";
  include "$root_path/lib/simplewiki/simplewiki.php";

  $creole_engine=new SimpleWiki();
}

function creole_html($text) {
  global $creole_engine;

  if(!$creole_engine)
    creole_init();
  if(!$creole_engine)
    return $text;
  
  return $creole_engine->get_html($text);
}
