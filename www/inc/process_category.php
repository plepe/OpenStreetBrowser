<?
class process_match {
}

class process_rule {
  function __construct($process_category, $node) {
  }

  function process() {
  }
}

class process_category {
  function __construct($category, $node) {
    $this->category=$category;
    $this->node=$node;
  }

  function process() {
    $cur=$this->node->firstChild;
    $id=$this->category->id;
    $ret=array();

    while($cur) {
      if($cur->nodeName=="rule") {
	$r=new process_rule($this, $cur);
	$data=$r->process();
	
	$ret=array_merge_recursive($ret, $data);
      }
      $cur=$cur->nextSibling;
    }

    $ret1=array();
    foreach($ret as $importance=>$x)
      foreach($x as $table=>$rules)
	$ret1[$table][$importance]=$rules;

    foreach($ret1 as $table=>$x) {
      sql_query("alter table planet_osm_$table drop column \"rule_$id\";");
      sql_query("alter table planet_osm_$table add column \"rule_$id\" text default null;");
      sql_query("create index planet_osm_{$table}_importance_{$id} on planet_osm_{$table}(\"rule_$id\");");
      sql_query("create index planet_osm_{$table}_notchecked_{$id} on planet_osm_{$table}(\"rule_$id\") where \"rule_$id\" is null;");

      $ret2=array();
      foreach($x as $importance=>$rules) {
	$ret[$importance][$table]['sql']=build_sql_match_table($rules, $table, $id, $importance);

	foreach($rules['rule_id'] as $i=>$d) {
	  $rule_id=$rules['rule_id'][$i];
	  $ret2['match'][$rule_id]=$rules['match'][$i];
	  $ret2['rule_id'][$rule_id]=$rules['rule_id'][$i];
	  $ret2['rule'][$rule_id]=$rules['rule'][$i];
	}
      }

      create_sql_classify_fun($ret2, $table, $id);
    }

    return $ret;
  }
}
