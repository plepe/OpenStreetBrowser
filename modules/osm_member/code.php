<?
function osm_member_info(&$chapters, $ob) {
  $members=$ob->members();

  if($members) {
    $content=array();

    // load all objects, so they exist in cache
    load_objects(array_map(function($x) {
      return $x['id'];
    }, $members));

    foreach($members as $m) {
      $member_id = $m['id'];
      $role = array_key_exists('role', $m) ? $m['role'] : null;

      $member=load_object($member_id);
      if($member) {
	$member->tags->set("#role", $role);
	$content[$member_id]=$member->export_array();
      }
    }

    $chapters[]=array(
      "id"=>"osm_member-members",
      "head"=>lang("osm_member-members"),
      "weight"=>5,
      "data"=>$content,
    );
  }

  $member_of=$ob->member_of();

  // load all objects, so they exist in cache
  load_objects(array_map(function($x) {
    return $x['id'];
  }, $member_of));

  if(sizeof($member_of)) {
    $content=array();
    foreach($member_of as $m) {
      $of_id = $of['id'];
      $role = $of['role'];

      $of=load_object($of_id);
      if($of) {
	$of->tags->set("#role", $role);
	$content[$of_id]=$of->export_array();
      }
    }

    $chapters[]=array(
      "id"=>"osm_member-member_of",
      "head"=>lang("osm_member-member_of"),
      "weight"=>5,
      "data"=>$content,
    );
  }
}

register_hook("info", "osm_member_info");
