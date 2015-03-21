<?php
function pgmapcss_compile($id) {
   global $db;
   global $pgmapcss;
   global $data_path;

   $config_options = "";
   if($pgmapcss['config_options'])
     $config_options = "-c {$pgmapcss['config_options']}";

   $f=adv_exec("{$pgmapcss['path']} {$config_options} --mode standalone -d'{$db['name']}' -u'{$db['user']}' -p'{$db['passwd']}' -H'{$db['host']}' -t'{$pgmapcss['template']}' '{$id}' 2>&1", "{$data_path}/categories/", array("LC_CTYPE"=>"en_US.UTF-8"));

   return $f[1];
}

register_hook("mapcss_saved", function(&$ret, $id) {
  $ret['compile_log'] = pgmapcss_compile($id);

  return $ret;
});
