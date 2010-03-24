<?
function parse_number($str) {
  if(preg_match("/^([0-9]+)([\.,]([0-9]+))? *(.*)$/", $str, $m)) {
    $val_n="$m[1].$m[3]";
    $val_u=$m[4];

    $numval=floatval($val_n);

    if(!$val_u)
      return $numval;

    $res=sql_query("select factor from dimension_units where id='$val_u'");
    if($elem=pg_fetch_assoc($res))
      $unit_fac=$elem[factor];
    else
      $unit_fac=1;

    return $numval*$unit_fac;
  }

  return null;
}
