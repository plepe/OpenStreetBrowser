function test_object() {
  this.inheritFrom=geo_object;
  this.inheritFrom();

  // name
  this.name=function() {
    return lang("test:test_title");
  }

  // info
  this.info=function(chapters) {
    chapters.push({
      head: lang("test:test_title"),
      weight: -1,
      content: "foobar"
    });
  }

  // constructor
  this.id="test_1234";
  this.type="test_object";
}

function test_init() {
  var test=new test_object();
  new info(test);
}

register_hook("init", test_init);
