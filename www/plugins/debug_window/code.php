<?
function debug_window_show() {
  global $debug_list;
  global $www_debug_level;
  global $debug_levels;
  $count=0;

  $document=new DOMDocument();

  $table=dom_create_append($document, "table");
  $table->setAttribute("class", "debug_window");

  foreach($debug_list as $entry) {
    if($entry['level']<$www_debug_level)
      continue;

    $tr=dom_create_append($table, "tr");
    $td=dom_create_append($tr, "td");
    $td->setAttribute("class", "date");
    dom_create_append_text($td, Date("Y-m-d H:i:s", $entry['time']));

    $td=dom_create_append($tr, "td");
    $td->setAttribute("class", "debug_levels");
    dom_create_append_text($td, $debug_levels[$entry['level']]);

    $td=dom_create_append($tr, "td");
    $td->setAttribute("class", "category");
    dom_create_append_text($td, $entry['category']);

    $td=dom_create_append($tr, "td");
    $td->setAttribute("class", "text");
    dom_create_append_text($td, $entry['text']);

    $count++;
  }

  if(!$count)
    return;

  print $document->saveHTML();
}

//register_hook("html_end", "debug_window_show");
