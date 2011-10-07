<?
class talk {
  public $page;
  public $tags;
  public $content;
  public $version=null;

  function __construct($param) {
    if(is_string($param)) {
      $this->page=$param;
      $param=array();
    }
    else {
      $this->page=$param['page'];
    }

    if($param['version'])
      $this->version=$param['version'];

    $this->tags=new tags();
    $this->load();
  }

  function load() {
    if($this->version) {
      $id=postgre_escape($this->version);
      $res=sql_query("select talk.* from talk where talk.version=$id");
    }
    else {
      $id=postgre_escape($this->page);
      $res=sql_query("select talk.* from talk_current join talk on talk_current.version=talk.version where talk_current.page=$id");
    }

    if(!$elem=pg_fetch_assoc($res)) {
      debug("Could not load page '{$this->page}'", "talk");
      return;
    }

    $this->tags->set_data(parse_hstore($elem['tags']));
    $this->content=$elem['content'];
    $this->version=$elem['version'];
  }

  function save($param) {
    global $db_central;

    $version=postgre_escape(uniqid());
    $tags=array_to_hstore($this->tags->data());
    $content=postgre_escape($this->content);
    $version_tags=array_to_hstore($param);
    $page=postgre_escape($this->page);

    $parent="null";
    if($this->version)
      $parent=postgre_escape($this->version);

    $sql ="begin;\n";
    $sql.="insert into talk values ($page, $version, $tags, $content, $version_tags, $parent);\n";
    $sql.="delete from talk_current where page=$page;\n";
    $sql.="insert into talk_current values ($page, $version, now());\n";
    $sql.="commit;";

    sql_query($sql, $db_central);
  }

  function history($start, $count) {
    if(!isset($start))
      $start=0;
    if(!isset($count))
      $count=10;

    $ret=array();
    $pos=0;
    $version=$this->version;

    while($version && ($pos<$start+$count)) {
      $res=sql_query("select * from talk where talk.version=".
                     postgre_escape($version));

      if(!($current=pg_fetch_assoc($res)))
	return $ret;

      if($pos>=$start) {
	$ret[$pos]['version']=$current['version'];
	$ret[$pos]['version_tags']=parse_hstore($current['version_tags']);
      }

      $pos++;
      $version=$current['parent'];
    }

    return $ret;
  }

  function export_json() {
    $ret=array();

    $ret['page']=$this->page;
    $ret['tags']=$this->tags->data();
    $ret['content']=$this->content;
    $ret['version']=$this->version;

    return $ret;
  }
}

function ajax_talk_load($param) {
  $page=new talk($param);
  return $page->export_json();
}

function ajax_talk_save($param, $xml, $postdata) {
  global $current_user;

  $page=new talk($param['page']);

  $page->content=$postdata;
  if($param['tags'])
    $page->tags->set_data($param['tags']);

  $version_tags=array();
  $version_tags['msg']=$param['msg'];
  $version_tags['user']=$current_user->username;
  $version_tags['date']=Date("c");

  return $page->save($version_tags);
}

function ajax_talk_history($param) {
  $page=new talk($param);

  return $page->history($param['start'], $param['count']);
}

function ajax_talk_browser($param) {
  $ret=array();

  $res=sql_query("select talk_current.page, talk.version_tags from talk_current left join talk on talk_current.version=talk.version order by now desc limit 10");
  while($elem=pg_fetch_assoc($res)) {
    $elem['version_tags']=parse_hstore($elem['version_tags']);
    $ret[]=$elem;
  }

  return $ret;
}

function talk_main_links($list) {
  $list[]=array(0, "<a href='javascript:talk_open_browser()'>".lang("talk_browser:name")."</a>");
}

function talk_recent_changes($list) {
  $res=sql_query("select * from talk order by version_tags->'date' desc limit 10");
  while($elem=pg_fetch_assoc($res)) {
    $elem['version_tags']=parse_hstore($elem['version_tags']);

    $entry=$elem['version_tags'];
    $entry['name']=sprintf("%s \"%s\"", lang("talk:name", 1), $elem['page']);
    $entry['plugin']="talk";
    $entry['href']="javascript:talk_show({ page: \"{$elem['page']}\", version: \"{$elem['version']}\"})";

    $list[]=$entry;
  }
}

register_hook("main_links", "talk_main_links");
register_hook("recent_changes_load", "talk_recent_changes");
