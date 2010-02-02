<?
include("conf.php");
//$sql=pg_connect("dbname=$db_name user=$db_user password=$db_passwd host=$db_host");
$request=unserialize(file_get_contents("/osm/skunkosm/request.save"));
$done=array();

foreach($request as $category=>$d1) {
  foreach($d1 as $importance=>$d2) {
    foreach($d2 as $table=>$d3) {
      if($d3['columns']) foreach($d3['columns'] as $column=>$values) {
	if((!$done[$table])||(!$done[$table][$column])) {
	  $qry="create index planet_osm_{$table}_{$column} on planet_osm_{$table}(\"{$column}\");";
	  print "$qry\n";
	  //pg_query($qry);

	  $done[$table][$column]=1;
	}
      }
    }
  }
}
