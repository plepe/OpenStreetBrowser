## INSTALL
```sh
git clone https://github.com/plepe/openstreetbrowser.git
cd openstreetbrowser
npm install
composer install
git submodule init
git submodule update
cp conf.php-dist conf.php
nano conf.php
npm run build
modulekit/build_cache
bin/download_dependencies
```

=> [more detailed Installation instructions](doc/INSTALL.md)

### Upgrade
```sh
cd openstreetbrowser
git pull
npm update
git submodule init
git submodule update
npm run build
```

## DEVELOPMENT
### Develop categories
```
rm -r node_modules/openstreetbrowser-categories-main
git clone https://github.com/plepe/openstreetbrowser-categories-main.git node_modules/openstreetbrowser-categories-main
```
You are welcome to send pull requests via Github!

### Category definition
There are currently two types of categories: `index` (for sub categories) and `overpass` (for OpenStreetMap data, loaded via an Overpass API request). Each of them is defined via a JSON structure. They can be combined into a single file.

#### Category 'index'
File: dir.json
```json
{
    "type": "index",
    "subCategories": [
        {
            "id": "foo"
        },
        {
            "id": "bar",
            "type": "overpass",
            "query": "node[amenity=bar]"
        }
    ]
}
```

This will define a category with the id 'dir' (from the file name) with two sub-categories: 'foo' (which will be loaded from the file `foo.json`) and 'bar' (which is defined inline as category of type 'overpass' and will show all nodes with the tag 'amenity' set to value 'bar' - see below for more details).

#### Category 'overpass'
File: foo.json
```json
{
    "type": "overpass",
    "query": {
        "12": "(node[highway~'^(motorway_junction)$'];way[highway~'^(motorway|trunk)$'];)",
        "14": "(node[highway~'^(motorway_junction|mini_roundabout|crossing)$'];way[highway~'^(motorway|trunk|primary)$'];)",
        "16": "(node[highway];way[highway];)"
    }
    "feature": {
        "style": {
            "color": "{% if tags.highway == 'motorway' %}#ff0000{% elseif tags.highway == 'trunk' %}#ff7f00{% elseif tags.highway == 'primary' %}#ffff00{% else %}#0000ff{% endif %}"
        },
        "markerSign": "{% if tags.highway == 'motorway_junction' %}↗{% elseif tags.highway == 'mini_roundabout' %}↻{% elseif tags.highway == 'crossing' %}▤{% endif %}",
        "title": "{{ localizedTag(tags, 'name') |default(localizedTag(tags, 'operator')) | default(localizedTag(tags, 'ref')) | default(trans('unnamed')) }}",
        "description": "{{ tagTrans('highway', tags.highway) }}",
        "body": "Foo value: {{ const.foo }}"
    },
    "const": {
        "foo": "foo value"
    }
}
```

This will define a category with the id 'foo' (from the file name). It will show some highway amenities, depending on the current zoom level.

The following values are possible for categories (the only mandatory value is query):
* query: either a string or an object of strings with the minimal zoom level as index. Give the Overpass API queries without the header (e.g. `[out:json]` or bounding box) and footer (e.g. `out meta geom` or so).
* minZoom: Show layer only from the given zoom level (default: 14). If `query` is an object and `minZoom` is not set, the lowest zoom level of a query will be used.
* maxZoom: Show layer only up to the given zoom level (default: no limit).
* feature: an object describing how the feature will be formated resp. styled.
  * style: a Leaflet style.
    * stroke: Whether to draw stroke along the path. Set it to false or empty string to disable borders on polygons or circles. (boolean, true)
    * width: Stroke width in pixels (number, 3)
    * color: Stroke color (string, '#3388ff')
    * opacity: Stroke opacity (number, 1.0)
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
  * title: the title of the feature popup, the object in the list and the details page. (default: localized tags for 'name', 'operator' or 'ref', default: 'unknown')
  * body: the body for the feature popup and the details page.
  * description: a short description shown in the list next to the title.
  * popupDescription: like description, but an alternative if the description in popups should be different from the list.
  * markerSign: a HTML string which will be shown within the marker and in the list. (default: '')
  * priority: a numeric value by which the elements in the list will be sorted (lower values first)
  * preferredZoom: At which max. zoom level will the map zoom when showing details
* const: an object variable which is available as prefix in twig functions.

All values in the "feature" section may use the [TwigJS language](doc/TwigJS.md) for evaluation.

### Hooks
With the function `register_hook` you can hook into several functions. The following hooks are available:

* `state-get`: modules can add values into the current state. Parameters: `state`: an object, which can be modified by modules.
* `state-apply`: when a state is applied to the app. Parameters: `state`: state which should be applied.
* `show-details`: called when details are being displayed. Parameters: data (see properties in doc/TwigJS.md), category, dom, callback.
* `hide-details`: called when the details page is left. No parameters.
* `show-popup`: called when a popup is being displayed. Parameters: data (see properties in doc/TwigJS.md), category, dom, callback.
* `options_save`: called when options are saved. Parameters: options (the new object), old_options (before save)
* `initFinish`: called when the app initialization finishes
