Categories can be created as YAML files. This is much simpler as JSON files, because you don't have to add all these quotes, you can use multi-line strings and allows adding comments.

A simple example ([Source](https://www.openstreetbrowser.org/dev/OpenStreetBrowser/examples/src/branch/master/example1.yaml)). It queries nodes, ways and relations with amenity=restaurant from OpenStreetMap (via Overpass API), starting from zoom level 15. `nwr` is short for `(node[amenity=restaurant];way[amenity=restaurant];relation[amenity=restaurant];)`:

```yaml
# This is necessary, it tells OSB that this uses OverpassQL for queries.
type: overpass
# From zoom level 15 on, load all node, ways and relations with amenity=restaurant.
query:
  15: nwr[amenity=restaurant]
```

Another example, showing fountains from z15 and (additionally) drinking_water from z17 ([Source](https://www.openstreetbrowser.org/dev/OpenStreetBrowser/examples/src/branch/master/example2.yaml)):
```yaml
type: overpass
query:
  # query as single line string:
  15: nwr[amenity=fountain]
  # query as multi line string:
  17: |
    (
      nwr[amenity=fountain];
      nwr[amenity=drinking_water];
    )
feature:
  # In the description, '{{ ... }}' is a TwigJS template. In this case it will
  # translate either the tag 'amenity=fountain' or 'amenity=drinking_water'
  # into a localized string:
  description: |
    {{ tagTrans('amenity', tags.amenity) }}
  # '{% ... %} is a code block in Twig, it can be used for setting variables,
  # if and for statements. This places different icons in the markers:
  markerSign: |
    {% if tags.amenity == 'fountain' %}
    ⛲
    {% elseif tags.amenity == 'drinking_water' %}
    🚰
    {% endif %}
```

Improving on the example above, we add a `const` block. The values of this block are available throughout the code ([Source](https://www.openstreetbrowser.org/dev/OpenStreetBrowser/examples/src/branch/master/example3.yaml)):
```yaml
type: overpass
query:
  15: nwr[amenity=fountain]
  # This query uses a regular expression to match either fountain or drinking_water:
  17: nwr[amenity~"^(fountain|drinking_water)$"]
feature:
  description: |
    {{ tagTrans('amenity', tags.amenity) }}
  # Here, the correct icon for display is read from the 'const' structure
  markerSign: |
    {{ const[tags.amenity].icon }}
  # We can use different markers depending on the type of item
  markerSymbol: |
    {{ markerPointer({ fillColor: const[tags.amenity].color }) }}
  # This is for the marker in the listing in the sidebar
  listMarkerSymbol: |
    {{ markerCircle({ fillColor: const[tags.amenity].color }) }}
const:
  fountain:
    icon: ⛲
    color: #0000ff
  drinking_water:
    icon: 🚰
    color: #007fff
```

Improving on the example above, we add a `info` block to show a map key. ([Source](https://www.openstreetbrowser.org/dev/OpenStreetBrowser/examples/src/branch/master/example4.yaml)):
```yaml
type: overpass
query:
  15: nwr[amenity=fountain]
  17: nwr[amenity~"^(fountain|drinking_water)$"]
feature:
  description: |
    {{ tagTrans('amenity', tags.amenity) }}
  # Here, the correct icon for display is read from the 'const' structure
  markerSign: |
    {{ const[tags.amenity].icon }}
  markerSymbol: |
    {{ markerPointer({ fillColor: const[tags.amenity].color }) }}
  listMarkerSymbol: |
    {{ markerCircle({ fillColor: const[tags.amenity].color }) }}
info: |
  <table>
  {% for value, data in const if data.zoom >= map.zoom %}
    <tr>
      <td>{{ markerCircle({ fillColor: data.color }) }}<div class='sign'>{{ data.icon }}</div></td>
      <td>{{ tagTrans('amenity', value) }}</td>
    </tr>
  {% endfor %}
  </table>"
const:
  fountain:
    icon: ⛲
    color: #0000ff
    zoom: 15
  drinking_water:
    icon: 🚰
    color: #007fff
    zoom: 17
```

Roads, with different color depending on its priority ([Source](https://www.openstreetbrowser.org/dev/OpenStreetBrowser/examples/src/branch/master/roads1.yaml)):
```yaml
type: overpass
name: 
  en: Roads 1 # English name of the category
query:
  9: way[highway~"^(motorway|trunk)$"];
  11: way[highway~"^(motorway|trunk|primary)$"];
  13: way[highway~"^(motorway|trunk|primary|secondary|tertiary)$"];
  15: way[highway~"^(motorway|trunk|primary|secondary|tertiary|road|residential)$"];
feature:
  description: |
    {{ tagTrans('highway', tags.highway) }}
  markerSymbol: # empty, to hide the marker
  listMarkerSymbol: line
  style:
    width: 4
    color: |
      {% if tags.highway == 'motorway' %}#ff0000
      {% elseif tags.highway == 'trunk' %}#ff3f00
      {% elseif tags.highway == 'primary' %}#ff7f00
      {% else %}#ffff00{% endif %}
```
