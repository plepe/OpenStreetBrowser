The following fields are possible for categories (the only mandatory value is query):

* Mandatory fields are bold

## Types
| Name | Description    |
|------|----------------|
| boolean | true or false. The following values are false: `undefined`, `null`, `false`, "false", 0, "0", "".
| color | A CSS color value, e.g. "#f00", "#ff0000", "#ff0000ff", "rgb(255, 0, 0)", rgb(255, 0, 0, 1), "red", ...
| float | a number, e.g. `1`, `0.5`.
| integer | a decimal number, e.g. `1`, `6`.
| string | an arbitrary text.
| length | a number with an optional unit. Availble units:<ul><li>'px' (default, a distance in display pixels)</li><li>'m' (meters in world coordinate system)</li><li>'%' (percentage of total length - if supported)</li>


## Global fields
| Field | Type | Default | Description
|-------|------|---------|-------------
| **query** | | - | either a string or an object of strings with the minimal zoom level as index. Give the Overpass API queries without the header (e.g. `[out:json]` or bounding box) and footer (e.g. `out meta geom` or so).
| minZoom | integer | - | Show layer only from the given zoom level (default: 14). If `query` is an object and `minZoom` is not set, the lowest zoom level of a query will be used.
| maxZoom | integer | no limit | Show layer only up to the given zoom level.
| feature | object (see below) | | an object describing how the feature will be formatted resp. styled.
| info | html string | | Map key of the category. [TwigJS](./TwigJS.md) is possible here.
| const | object | | an object variable which is available as prefix in twig functions.
| filter | object | | optional filters for the category, see [Filters](./Filters.md).
| config | object | | optional configuration for the category, see [Filters](./Filters.md).

Additional fields:

| Field | Type | Default | Description
|-------|------|---------|-------------
| members | boolean | false | Load members of the queried items (relation members *or* way nodes)
| memberFeature | object (see below) | | an object describing how children of the queried items (relation members *or* way nodes) are rendered. Requires `members` set to true.


## Feature fields
All of these fields can use [TwigJS](./TwigJS.md).

| Field | Type | Default | Description
|-------|------|---------|-------------
| title | html string | *localized tags for 'name', 'operator' or 'ref'* | the title of the feature popup, the object in the list and the details page.
| body | html string | *empty string* | the body for the feature popup and the details page.
| description | html string | *empty string* | a short description shown in the list on top of the title.
| details | html string | *empty string* | Additional information shown in the list on the right of the title.
| markerSign | html string | *empty string* | a HTML string which will be shown within the marker and in the list (usally an [Icon](./Icons.md)).
| markerSymbol | html string | *Pin* | The pin symbol pointing to the map feature. You can use the TwigJS function `markerPointer` to format the pin, e.g.: `{{ markerPointer({ fillColor: '#ff0000' })`. All [CSS parameters for SVG Paths](https://developer.mozilla.org/en-US/docs/Web/SVG/Tutorial/Fills_and_Strokes#using_css) are available.
| listMarkerSymbol | 'line' *or* 'polygon' *or* html string | *Circle* | The symbol in the list. If this is 'line' or 'polygon', an icon from the `style` of the object will be generated. Alternatively, you can use the TwigJS function `markerCircle` to format the symbol, e.g.: `{{ markerCircle({ fillColor: '#ff0000' })`. All [CSS parameters for SVG Paths](https://developer.mozilla.org/en-US/docs/Web/SVG/Tutorial/Fills_and_Strokes#using_css) are available.
| styles | comma-separated list of strings | default | Which of the defined styles will be drawn.
| style | object (see below) | |  A style describing how the map feature should be shown on the map. Synonymous to `style:default`.
| style:*X* | object (see below) | | An alternative/additional style, describing how the map feature should be shown on the map. Will only be shown, if *X* is included in `styles`.

Additional fields:

| Field | Type | Default | Description
|-------|------|---------|-------------
| popupDescription | html string | *empty string* | like description, but an alternative if the description in popups should be different from the list.
| popupDetails | html string | *empty string* | like `details`, but an alternative if the details in popups should be different from the list.
| priority | integer | 0 | a numeric value by which the elements in the list will be sorted (lower values first)
| preferredZoom | integer | 16 | At which max. zoom level will the map zoom when showing details
| exclude | boolean | false | if value evaluates to true, will not be shown on the map (and the list)
| listExclude | boolean | false | if value evaluates to true, will not be shown in the list (priority over exclude)

## Style and `style:*X*`` parameters
All of these fields can use [TwigJS](./TwigJS.md).

| Field | Type | Default | Description
|-------|------|---------|-------------
| stroke | boolean | true | Whether to draw stroke along the path. Set it to false or empty string to disable borders on polygons or circles.
| width | length | 3 | Stroke width, optionally with unit ('px' for width in screen pixels (default) or 'm' for width in world meters).
| color | color | #3388ff | Stroke color
| opacity | float | 1.0 | Stroke opacity
| offset | length | 0 | Offset stroke to left or right ('px' for width in screen pixels (default) or 'm' for width in world meters).
| dashArray | string | *null* | stroke dash pattern
| dashOffset | integer | *null* | distance into the dash pattern to start dash
| fill | boolean | *depends* | whether to fill the path with color. Set it to false or empty string to disable filling on polygons or circles.
| fillColor | color | *value of 'color'* | Fill color. Defaults to the value of the color option.
| fillOpacity | float | 0.2 | Fill opacity.
| nodeFeature | Marker *or* Circle *or* CircleMarker | CircleMarker | (nodes only) Which type of feature to show on nodes.
| radius | length | 10 | (nodes with nodeFeature 'Circle' or 'CircleMarker' only) Radius of the circle (for 'Circle': in meters, for 'CircleMarker': in pixels).
| zIndex | float | 0.0 | Order of features (higher = front). (this impacts performance, using 'pane' is recommended).
| text | string | *null* | Label to show along the line (*null* for no label). (this impacts performace, as SVG labels are used).
| textRepeat | boolean | false | Specifies if the text should be repeated along the polyline.
| textCenter | boolean | false | Centers the text according to the polyline's bounding box.
| textOffset | float | 0 | Set an offset to position text relative to the polyline.
| textBelow | boolean | false | Show text below the path.
| textFill | color | black | Color of the text.
| textFillOpacity | opacity | 1.0 | Text opacity.
| textFontWeight | string | normal | Boldness or lightness of the glyphs used to render the text.
| textFontSize | font size | 12px | Font size of the text.
| textLetterSpacing | float | 0 | Extra letter spacing of the text.
| pattern *or* pattern*X* | arrowHead *or* dash *or* marker | *empty string* | If set, draw a pattern along the (out)line. Use a suffix (e.g. *X*) to show several patterns on the same line.
| pattern*X*-offset | length | 0 | Offset of the first pattern symbol, from the start point of the line.
| pattern*X*-endOffset | length | 0 | Minimum offset of the last pattern symbol, from the end point of the line.
| pattern*X*-repeat | length | 0 | Repetition interval of the pattern symbols. Defines the distance between each consecutive symbol's anchor point.
| pattern*X*-polygon | boolean | true | arrowHead only: draw a line (false) or a polygon (true).
| pattern*X*-pixelSize | length | 10 | arrowHead and dash only: size of pattern
| pattern*X*-headAngle | integer (degrees) | 60 | arrowHead only: Angle of the digits
| pattern*X*-angleCorrection | integer (degrees) | 0 | arrowHead and marker only: rotate pattern on the line.
| pattern*X*-rotate | boolean | false | marker only: should the marker be rotated to match the line.
| pattern*X*-path-* | *depends* | *depends* | Options for the path, e.g. pattern*X*-path-width, pattern*X*-path-color.

Additional fields:

| Field | Type | Default | Description
|-------|------|---------|-------------
| lineCap | butt *or* round *or* square | round | shape at end of the stroke
| lineJoin | arcs *or* bevel *or* miter *or* miter-clip *or* round | round | shape at corners of the stroke
| fillRule | evenodd *or* oddeven | evenodd | define how the inside of a shape is determined.
| smoothFactor | float | 1.0 | (unclosed ways only) How much to simplify the polyline on each zoom level.
| noClip | boolean | false | (unclosed ways only) Disable polyline clipping. (boolean, false)
| pane | overlayPane *or* hover *or* selected *or* casing *or self defined* | overlayPane | show vector on the specified pane.
