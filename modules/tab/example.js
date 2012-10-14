function tab_example_init() {
  var w=new win("options_win");
  var m=new tab_manager(w.content);
  var t1=new tab({ name: "Foobar1" });
  var t2=new tab({ name: "Foobar2" });
  var t3=new tab({ name: "Foobar3" });
  m.register_tab(t1);
  m.register_tab(t2);
  m.register_tab(t3);
  t1.activate();
  dom_create_append_text(t1.content, "huhu text bla 1");
  dom_create_append_text(t2.content, "huhu text bla 2");
  dom_create_append_text(t3.content, "huhu text bla 3");
}
register_hook("init", tab_example_init);
