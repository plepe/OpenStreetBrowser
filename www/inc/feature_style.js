function get_style(request, obj, member, role) {
  if(obj.get_tag("type")=="route") {
    if(role=="forward") {
      return {
	      strokeWidth: 4,
	      strokeColor: "#00007f",
	      externalGraphic: "arrow_forward.png"
	    };
    }
    if(role=="backward") {
      return {
	      strokeWidth: 4,
	      strokeColor: "#007f00"
	    };
    }
  }

  return {
	    strokeWidth: 4,
	    strokeColor: "black"
	  };
}

function register_style(obj_constr, member_constr, role_constr) {
}
