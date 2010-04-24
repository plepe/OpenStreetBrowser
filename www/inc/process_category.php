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
    $ret=array();

    while($cur) {
      if($cur->nodeName=="rule") {
	$r=new process_rule($this, $cur);
	$data=$r->process();
	
	$ret=array_merge_recursive($ret, $data);
      }
      $cur=$cur->nextSibling;
    }

    foreach($ret as $importance=>$x) {
      foreach($x as $table=>$rules) {
	$ret[$importance][$table]['sql']=build_sql_match_table($rules, $table, $this->category->id, $importance);
      }
    }

    return $ret;
  }
}
