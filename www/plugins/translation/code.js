var translation_win;

function translation_open() {
  translation_win=new win({ title: lang("translation:name") });
  ajax("translation_read", { lang: "en" }, translation_open_next1);
}

function translation_open_next1(ret) {
  ret=ret.return_value;
  alert(print_r(ret));
}

function translation_files_list() {
}
