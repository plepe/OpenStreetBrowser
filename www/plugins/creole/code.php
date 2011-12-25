<?
global $creole_engine;

function creole_init() {
  global $creole_engine;

  if($creole_engine)
    return;

  include "simplewiki/muster_simplewiki_docnode.php";
  include "simplewiki/simplewiki_docnode.php";
  include "simplewiki/muster_simplewiki_parser.php";
  include "simplewiki/simplewiki_parser.php";
  include "simplewiki/muster_simplewiki_emitter.php";
  include "simplewiki/simplewiki_emitter.php";
  include "simplewiki/muster_simplewiki.php";
  include "simplewiki/simplewiki.php";

  $creole_engine=new SimpleWiki();
}

function creole_html($text) {
  global $creole_engine;

  if(!$creole_engine)
    creole_init();
  
  return $creole_engine->get_html($text);
}
