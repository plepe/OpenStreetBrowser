<?
class infolist_boundaries extends infolist {
  function show_info($bounds) {
    if($bounds[zoom]>=8)
      $admin_level=5;
    if($bounds[zoom]>=9)
      $admin_level=5;
    if($bounds[zoom]>=10)
      $admin_level=6;
    if($bounds[zoom]>=11)
      $admin_level=7;
    if($bounds[zoom]>=12)
      $admin_level=8;
    if($bounds[zoom]>=13)
      $admin_level=9;
    if($bounds[zoom]>=14)
      $admin_level=10;
    if($bounds[zoom]>=15)
      $admin_level=11;

    $qry="select rel_id, admin_level, name from planet_osm_boundaries where way&&PolyFromText('POLYGON(($bounds[left] $bounds[top], $bounds[right] $bounds[top], $bounds[right] $bounds[bottom], $bounds[left] $bounds[bottom], $bounds[left] $bounds[top]))', $SRID) and admin_level<='$admin_level' order by admin_level;";
  }
}

