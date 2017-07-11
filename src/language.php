<?php
register_hook('options_save', function ($options) {
  if (array_key_exists('ui_lang', $options)) {
    $_SESSION['ui_lang'] = $options['ui_lang'];
  }
});
