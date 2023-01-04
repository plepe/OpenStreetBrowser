The following values are possible for categories (the only mandatory value is query):
* query: either a string or an object of strings with the minimal zoom level as index. Give the Overpass API queries without the header (e.g. `[out:json]` or bounding box) and footer (e.g. `out meta geom` or so).
* minZoom: Show layer only from the given zoom level (default: 14). If `query` is an object and `minZoom` is not set, the lowest zoom level of a query will be used.
* maxZoom: Show layer only up to the given zoom level (default: no limit).
* feature: an object describing how the feature will be formatted resp. styled.
  * style: a Leaflet style.
    * stroke: Whether to draw stroke along the path. Set it to false or empty string to disable borders on polygons or circles. (boolean, true)
    * width: Stroke width, optionally with unit ('px' for width in screen pixels (default) or 'm' for width in world meters). Default: '3px'.
    * color: Stroke color (string, '#3388ff')
    * opacity: Stroke opacity (number, 1.0)
    * offset: Offset stroke to left or right ('px' for width in screen pixels (default) or 'm' for width in world meters). Default: '0px'.
    * lineCap: shape at end of the stroke (string, 'round')
    * lineJoin: shape at corners of the stroke (string, 'round')
    * dashArray: stroke dash pattern (string, null)
    * dashOffset: distrance into the dash pattern to start dash (string, null)
    * fill: whether to fill the path with color. Set it to false or empty string to disable filling on polygons or circles. (boolean, depends)
    * fillColor: Fill color. Defaults to the value of the color option. (string)
    * fillOpacity: Fill opacity. (number, 0.2)
    * fillRule: define how the inside of a shape is determined. (string, 'evenodd')
    * smoothFactor: (unclosed ways only) How much to simplify the polyline on each zoom level. (number, 1.0)
    * noClip: (unclosed ways only) Disable polyline clipping. (boolean, false)
    * nodeFeature: (nodes only) Which type of feature to show on nodes. Valid values: 'Marker', 'Circle', 'CircleMarker'. (string, 'CircleMarker')
    * radius: (nodes with nodeFeature 'Circle' or 'CircleMarker' only) Radius of the circle (for 'Circle': in meters, for 'CircleMarker': in pixels, default: 10). (number)
    * pattern: false/empty: no pattern, 'arrowHead', 'dash', 'marker'
    * pattern-offset: Offset of the first pattern symbol, from the start point of the line. Default: 0.
    * pattern-endOffset: Minimum offset of the last pattern symbol, from the end point of the line. Default: 0.
    * pattern-repeat: Repetition interval of the pattern symbols. Defines the distance between each consecutive symbol's anchor point.
    * pattern-polygon: true/false (arrowHead only)
    * pattern-pixelSize: size of pattern (arrowHead and dash only)
    * pattern-headAngle: Angle of the digits (arrowHead only)
    * pattern-angleCorrection: degrees (arrowHead and marker only)
    * pattern-rotate: false (marker only)
    * pattern-path-*: Options for the path, e.g. pattern-path-width, pattern-path-color.
    * pane: show vector on the specified pane (usually defined: 'overlayPane' (default), 'hover', 'selected', 'casing')
  * title: the title of the feature popup, the object in the list and the details page. (default: localized tags for 'name', 'operator' or 'ref', default: 'unknown')
  * body: the body for the feature popup and the details page.
  * description: a short description shown in the list next to the title.
  * popupDescription: like description, but an alternative if the description in popups should be different from the list.
  * markerSign: a HTML string which will be shown within the marker and in the list. (default: '')
  * priority: a numeric value by which the elements in the list will be sorted (lower values first)
  * preferredZoom: At which max. zoom level will the map zoom when showing details
  * exclude: if value evaluates to true, will not be shown on the map (and the list)
  * listExclude: if value evaluates to true, will not be shown in the list (priority over exclude)
* const: an object variable which is available as prefix in twig functions.


