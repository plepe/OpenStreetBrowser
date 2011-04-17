<?
function osm_member_info(&$chapters, $ob) {
  $members=$ob->members();

  if($members) {
    $content=array();
    foreach($members as $member_id=>$role) {
      $member=load_object($member_id);
      if($member) {
	$member->tags->set("#role", $role);
	$content[$member_id]=$member->export_array();
      }
    }

    $chapters[]=array(
      "id"=>"osm_member-members",
      "head"=>lang("members"),
      "weight"=>5,
      "data"=>$content,
    );
  }
}

register_hook("info", "osm_member_info");
