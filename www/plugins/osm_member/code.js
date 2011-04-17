function osm_member_show_members(div, data) {
  var ret=[];

  for(var i in data) {
    var ob=new osm_object(data[i]);

    var entry={};
    entry.name=ob.name();
    entry.href="#"+i;
    if(ob.tags.get("#role"))
      entry.type=ob.tags.get("#role");
    if(ob.tags.get("#geo"))
      entry.highlight=ob.tags.get("#geo");
    if(ob.tags.get("#geo:center"))
      entry.highlight_center=ob.tags.get("#geo:center");

    ret.push(entry);
  }

  new list( div, ret );
}

function osm_member_show_member_of(div, data) {
  var ret=[];

  for(var i in data) {
    var ob=new osm_object(data[i]);

    var entry={};
    entry.name=ob.name();
    entry.href="#"+i;
    if(ob.tags.get("#role"))
      entry.type=ob.tags.get("#role");
    if(ob.tags.get("#geo"))
      entry.highlight=ob.tags.get("#geo");
    if(ob.tags.get("#geo:center"))
      entry.highlight_center=ob.tags.get("#geo:center");

    ret.push(entry);
  }

  new list( div, ret );
}

function osm_member_info_show(info, ob) {
  for(var i=0; i<info.chapters.length; i++) {
    var chapter=info.chapters[i];

    if(chapter.id&&(chapter.id=="osm_member-members")) {
      osm_member_show_members(chapter.content_node, chapter.data);
    }

    if(chapter.id&&(chapter.id=="osm_member-member_of")) {
      osm_member_show_member_of(chapter.content_node, chapter.data);
    }
  }
}

register_hook("info_show", osm_member_info_show);
