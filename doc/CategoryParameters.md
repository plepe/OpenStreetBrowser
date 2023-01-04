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
| const | object | | an object variable which is available as prefix in twig functions.

## Feature fields
| Field | Type | Default | Description
|-------|------|---------|-------------
| title | html string | *localized tags for 'name', 'operator' or 'ref'* | the title of the feature popup, the object in the list and the details page.
| body | html string | *empty string* | the body for the feature popup and the details page.
| description | html string | *empty string* | a short description shown in the list next to the title.
| popupDescription | html string | *empty string* | like description, but an alternative if the description in popups should be different from the list.
| markerSign | string / html string | *empty string* | a HTML string which will be shown within the marker and in the list.
| priority | integer | 0 | a numeric value by which the elements in the list will be sorted (lower values first)
| preferredZoom | integer | 16 | At which max. zoom level will the map zoom when showing details
| exclude | boolean | false | if value evaluates to true, will not be shown on the map (and the list)
| listExclude | boolean | false | if value evaluates to true, will not be shown in the list (priority over exclude)
| style | object (see below) | |  a Leaflet style.

## Style parameters
| Field | Type | Default | Description
|-------|------|---------|-------------
| stroke | boolean | true | Whether to draw stroke along the path. Set it to false or empty string to disable borders on polygons or circles.
| width | length | 3 | Stroke width, optionally with unit ('px' for width in screen pixels (default) or 'm' for width in world meters).
| color | color | #3388ff | Stroke color
| opacity | float | 1.0 | Stroke opacity
| offset | length | 0 | Offset stroke to left or right ('px' for width in screen pixels (default) or 'm' for width in world meters).
| lineCap | butt *or* round *or* square | round | shape at end of the stroke
| lineJoin | arcs *or* bevel *or* miter *or* miter-clip *or* round | round | shape at corners of the stroke
| dashArray | string | *null* | stroke dash pattern
| dashOffset | integer | *null* | distance into the dash pattern to start dash
| fill | boolean | *depends* | whether to fill the path with color. Set it to false or empty string to disable filling on polygons or circles.
| fillColor | color | *value of 'color'* | Fill color. Defaults to the value of the color option.
| fillOpacity | float | 0.2 | Fill opacity.
| fillRule | evenodd *or* oddeven | evenodd | define how the inside of a shape is determined.
| smoothFactor | float | 1.0 | (unclosed ways only) How much to simplify the polyline on each zoom level.
| noClip | boolean | false | (unclosed ways only) Disable polyline clipping. (boolean, false)
| nodeFeature | Marker *or* Circle *or* CircleMarker | CircleMarker | (nodes only) Which type of feature to show on nodes.
| radius | length | 10 | (nodes with nodeFeature 'Circle' or 'CircleMarker' only) Radius of the circle (for 'Circle': in meters, for 'CircleMarker': in pixels).
| pane | overlayPane *or* hover *or* selected *or* casing *or self defined* | overlayPane | show vector on the specified pane.
| pattern  | arrowHead *or* dash *or* marker | *empty string* | If set, draw a pattern along the (out)line.
| pattern-offset | length | 0 | Offset of the first pattern symbol, from the start point of the line.
| pattern-endOffset | length | 0 | Minimum offset of the last pattern symbol, from the end point of the line.
| pattern-repeat | length | 0 | Repetition interval of the pattern symbols. Defines the distance between each consecutive symbol's anchor point.
| pattern-polygon | boolean | true | arrowHead only: draw a line (false) or a polygon (true).
| pattern-pixelSize | length | 10 | arrowHead and dash only: size of pattern
| pattern-headAngle | integer (degrees) | 60 | arrowHead only: Angle of the digits
| pattern-angleCorrection | integer (degrees) | 0 | arrowHead and marker only: rotate pattern on the line.
| pattern-rotate | boolean | false | marker only: should the marker be rotated to match the line.
| pattern-path-* | *depends* | *depends* | Options for the path, e.g. pattern-path-width, pattern-path-color.
