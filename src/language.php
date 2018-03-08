<?php
register_hook('options_save', function ($options) {
  if (array_key_exists('ui_lang', $options)) {
    $_SESSION['ui_lang'] = $options['ui_lang'];
  }
});

register_hook('lang_report_non_translated', function ($strings, $ui_lang) {
  if (!is_writeable('data')) {
    return;
  }

  $db = new PDO('sqlite:data/lang.db');

  $res = $db->query('select 1 from lang_non_translated');
  if (!$res) {
    $query = <<<EOT
create table lang_non_translated (
  str           varchar(255)    not null,
  lang          varchar(32)     not null,
  count         integer         not null default 0,
  primary key(str, lang)
);
EOT;
    $db->query($query);
  }

  foreach ($strings as $k => $count) {
    $query = 'insert or replace into lang_non_translated values (' . $db->quote($k) . ', ' . $db->quote($ui_lang) . ', coalesce((select count + ' . $db->quote($count) . ' from lang_non_translated where str=' . $db->quote($k) . ' and lang=' . $db->quote($ui_lang) . '), ' . $db->quote($count) . '))';
    $db->query($query);
  }
});
