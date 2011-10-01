<?
class talk {
  public $page;
  public $tags;
  public $content;
  public $version;

  function __construct($page) {
    $this->page=$page;
    $this->tags=new tags();
    $this->load();
  }

  function load() {
    $id=postgre_escape($this->page);
    $res=sql_query("select talk.* from talk_current join talk on talk_current.version=talk.version where talk_current.page=$id");

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
  $page=new talk($param['page']);
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
