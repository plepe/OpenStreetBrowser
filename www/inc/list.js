// list - shows a list of objects in a div
// * parameters:
// div - a domnode of the div, where the list should be displayed
// elements - an array of elements, described later. add a 'null' value as last
//   element to indicate further elements
// request_more - an (optional) function, which will be called when more
//   elements are needed. either return an array directly or return 0 and call
//   function recv later to add more elements. set the last value 'null' to
//   indicate more elements.
// options - a hash array of additional options:
//   show_count: amount of elements to show before 'more'
// * properties
//   shown - How many elements are shown right now
//   should_shown - How many elements should be shown right now (waiting for
//     recv)
//
// elements: An array of hash arrays, looking like this:
// [ { name: 'The Old Pub', href='#node_1234', icon: 'pub' }, ..., null ]
//

var list_default_options={ show_count: 10 };

function list(div, elements, request_more, options) {
  // recv - receive more elements
  // * parameters:
  // elements - an array of elements
  this.recv=function(more_elements) {
  }

  // constructor
  this.shown=0;
  this.should_shown=options.show_count;
}
