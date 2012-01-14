var keyshort_active;

function keyshort_keydown(ev) {
  if(!ev)
    ev=event;

  // if an element has the focus, ignore
  if(document.activeElement!=document.body)
    return;

  // tell hooks about keydown
  var done=[];
  call_hooks("keyshort_keydown", done, ev);
}

function keyshort_keyup(ev) {
  if(!ev)
    ev=event;

  // if an element has the focus, ignore
  if(document.activeElement!=document.body)
    return;

  // tell hooks about keyup
  var done=[];
  call_hooks("keyshort_keyup", done, ev);
}

// activate keyboard shortcuts only, if document.activeElement is supported
// by the current browser
if(document.activeElement) {
  window.onkeydown=keyshort_keydown;
  window.onkeyup=keyshort_keyup;
  keyshort_active=true;
}
else {
  keyshort_active=false;
}
