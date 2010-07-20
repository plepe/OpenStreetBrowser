<?
class process_match {
}

class process_rule {
  function __construct($process_category, $node) {
    $this->tags=new tags();
    $this->tags->readDOM($node);
    $this->id=$node->getAttribute("id");
  }

  function process() {
    global $importance_levels;
    global $postgis_tables;

    $ret=array();

    $tables=$this->tags->get("type");
    if($tables)
      $tables=explode(";", $tables);
    else
      $tables=array("polygon", "point");
    $tables=array_intersect($tables, array_keys($postgis_tables));
    if(!sizeof($tables)) {
      return "No valid types";
    }

    $importance=$this->tags->get("importance");
    if(!$importance)
      $importance="local";
    elseif(!in_array($importance, $importance_levels))
      $importance="*";

    $match=parse_match($this->tags->get("match"));

    if(is_string($match))
      return $match;

    foreach($tables as $table) {
      if($table=='_')
	continue;

      if($importance=="*") {
	foreach($importance_levels as $imp_lev) {
	  $ret[$imp_lev][$table]['match'][$this->id]=$match;
	  $ret[$imp_lev][$table]['rule'][$this->id]=$this->tags;
	  $ret[$imp_lev][$table]['rule_id'][$this->id]=$this->id;
	}
      }
      else {
	$ret[$importance][$table]['match'][$this->id]=$match;
	$ret[$importance][$table]['rule'][$this->id]=$this->tags;
	$ret[$importance][$table]['rule_id'][$this->id]=$this->id;
      }
    }

    return $ret;
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
    $ret=array('_'=>array("errors"=>array()));

    while($cur) {
      if($cur->nodeName=="rule") {
	$r=new process_rule($this, $cur);
	$data=$r->process();

	if(is_string($data)) {
	  $ret['_']['errors'][]="Error in rule $r->id: $data";
	}
	
	$ret=array_merge_recursive($ret, $data);
      }
      $cur=$cur->nextSibling;
    }

    $ret1=array();
    foreach($ret as $importance=>$x)
      foreach($x as $table=>$rules)
	$ret1[$table][$importance]=$rules;

    tmp_delete_indexes($id);

    foreach($ret1 as $table=>$x) {
//      sql_query("alter table planet_osm_$table drop column \"rule_$id\";");
//      sql_query("alter table planet_osm_$table add column \"rule_$id\" text default null;");
//      sql_query("create index planet_osm_{$table}_importance_{$id} on planet_osm_{$table}(\"rule_$id\");");
//      sql_query("create index planet_osm_{$table}_notchecked_{$id} on planet_osm_{$table}(\"rule_$id\") where \"rule_$id\" is null;");

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

      $ret['_']['classify_fun']=create_sql_classify_fun($ret2, $table, $id);
    }

    delete_indexes($id);

    return $ret;
  }
}
