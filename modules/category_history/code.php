<?
function ajax_category_history($param) {
 return category_history($param['id']);
}

function category_history_recent_changes(&$list) {
  $res=sql_query("select * from category where version_tags ? 'date' order by version_tags->'date' desc limit 50");
  while($elem=pg_fetch_assoc($res)) {
    $elem['version_tags']=parse_hstore($elem['version_tags']);
    $t=new tags(parse_hstore($elem['tags']));

    $entry=$elem['version_tags'];
    $entry['name']=sprintf("%s \"%s\"", lang("category", 1), $t->get_lang("name"));
    $entry['msg']=coalesce($elem['version_tags']['msg'], "no message");
    $entry['plugin']="category";
    $entry['href']="javascript:category_show(\"osm:{$elem['category_id']}\", { version: \"{$elem['version']}\"})";

    $list[]=$entry;
  }
}

register_hook("recent_changes_load", "category_history_recent_changes");
