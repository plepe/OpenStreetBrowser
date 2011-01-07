<?
Header("Content-type: text/plain; charset=utf-8");
require "../../conf.php";
$ui_lang=$_GET['ui_lang'];

function lang() {
}

function esc($d) {
  if(is_array($d)) {
    print "array( \"".implode("\", \"", $d)."\" )";
  }
  else {
    return "\"$d\"";
  }
}

function template_lang_file($src, $dst) {
  global $count_done;
  global $count_missing;
  global $root_path;

  $lang_str=array();
  @include "$root_path/$src";
  $lang_str_src=$lang_str;
  if(!$lang_str_src)
    $lang_str_src=array();

  $lang_str=array();
  @include "$root_path/$dst";
  $lang_str_dst=$lang_str;
  if(!$lang_str_dst)
    $lang_str_dst=array();

  if((!sizeof($lang_str_src))&&(!sizeof($lang_str_dst)))
    return;

  print "==== File: $dst ====\n";

  if(file_exists("$root_path/$src")) {
    $f=fopen("$root_path/$src", "r");
    while($r=fgets($f)) {
      if(eregi("^( *)\\\$lang_str\[\"([^\"]*)\"\]", $r, $m)) {
	if($l=$lang_str_dst[$m[2]]) {
	  print "$m[1]\$lang_str[\"$m[2]\"]=";
	  print esc($l);
	  print ";\n";
	  unset($lang_str_dst[$m[2]]);

	  $count_done++;
	}
	else {
	  print "#$m[1]\$lang_str[\"$m[2]\"]=";
	  print esc($lang_str_src[$m[2]]);
	  print ";\n";

	  $count_missing++;
	}
      }
      else {
	print $r;
      }
    }
    fclose($f);
  }
  
  if(sizeof($lang_str_dst)) {
    print "\n";
    print "// The following \$lang_str are not defined in $src and might be \n";
    print "// deprecated/mislocated/wrong:\n";
    foreach($lang_str_dst as $k=>$v) {
      print "\$lang_str[\"$k\"]=".esc($v).";\n";
    }
  }

  print "\n";
}

$count_done=0;
$count_missing=0;

template_lang_file("www/lang/en.php", "www/lang/{$ui_lang}.php");
template_lang_file("www/lang/tags_en.php", "www/lang/tags_{$ui_lang}.php");
$d=opendir("$root_path/www/plugins");
while($f=readdir($d))
  template_lang_file("www/plugins/$f/lang_en.php", "www/plugins/$f/lang_{$ui_lang}.php");
closedir($d);

print "==== Statistics ====\n";
print "* Done: $count_done\n";
print "* Missing: $count_missing\n";
print "* Goal: ".sprintf("%.0f", ($count_done/($count_done+$count_missing)*100))."%\n";
