## INSTALL
```sh
git clone https://github.com/plepe/openstreetbrowser.git
cd openstreetbrowser
npm install
git submodule init
git submodule update
cp conf.php-dist conf.php
nano conf.php
npm run build
modulekit/build_cache
```

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
        "body": "{{ tagTrans('highway', tags.highway) }}<br/>Foo value: {{ const.foo }}"
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
    * weight: Stroke width in pixels (number, 3)
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
  * title: the title of the feature popup, the object in the list and the details page. (default: localized tags for 'name', 'operator' or 'ref', default: 'unknown')
  * body: the body for the feature popup and the details page.
  * description: a short description shown in the list next to the title.
  * markerSign: a HTML string which will be shown within the marker and in the list. (default: '')
  * priority: a numeric value by which the elements in the list will be sorted (lower values first)
* const: an object variable which is available as prefix in twig functions.

#### TwigJS templates
All values in the "feature" section may use the TwigJS language for evaluation.

The following properties are available:
* id (the id of the object is always available, prefixed 'n' for nodes, 'w' for ways and 'r' for relations; e.g. 'n1234')
* osm_id (the numerical id of the object)
* layer_id (the id of the category)
* type ('node', 'way' or 'relation')
* tags.* (all tags are available with the prefix tags., e.g. tags.amenity)
* meta.timestamp (timestamp of last modification)
* meta.version (version of the object)
* meta.changeset (ID of the changeset, the object was last modified in)
* meta.user (Username of the user, who changed the object last)
* meta.uid (UID of the user, who changed the object last)
* map.zoom (Current zoom level)
* const.* (Values from the 'const' option)

There are several extra functions defined for the TwigJS language:
* function `keyTrans`: return the translation of the given key. Parameters: key (required, e.g. 'amenity').
* function `tagTrans`: return the translation of the given tag. Parameters: key (required, e.g. 'amenity'), value (required, e.g. 'bar'), count (optional, default 1).
* function `tagTranList`: return the translations of the given tag for tags with multiple values separated by ';' (e.g. 'cuisine'). Parameters: key (required, e.g. 'cuisine'), value (required, e.g. 'kebab' or 'kebab;pizza;noodles;burger').
* function `localizedTag`: return a localized tag if available (e.g. 'name:de' for the german translation of the tag). Parameters: tags (the tags property), key prefix (e.g. 'name'). Which language will be returned depends on the "data language" which can be set via Options. If no localized tag is available, the tag value itself will be returned (e.g. value of 'name').
* function `trans`: return the translation of the given string (e.g. 'save', 'unknown', 'unnamed', ...). Parameters: string (the string to translate).

Notes:
* Variables will automatically be HTML escaped, if not the filter raw is used, e.g.: {{ tags.name|raw }}
* The templates will be rendered when the object becomes visible and when the zoom level changes.
* If you set an arbitrary value within a twig template (e.g.: {% set foo = "bar" %}), it will also be available in further templates of the same object by using (e.g.: {{ foo }}). The templates will be evaluated in the order as they are defined.
