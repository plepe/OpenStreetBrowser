Categories can be created as YAML files. This is much simpler as JSON files and allows adding comments.

A simple example:

```yaml
type: overpass
# From zoom level 15 on, load all node, ways and relations with amenity=restaurant.
query:
  15: nwr[amenity=restaurant]
```

Another example, showing fountains from z15 and (additionally) drinking_water from z17:
```yaml
type: overpass
query:
  15: nwr[amenity=fountain]
  17: |
    (
      nwr[amenity=fountain];
      nwr[amenity=drinking_water];
    )
feature:
  description: |
    {{ tagTrans('amenity', tags.amenity) }}
  markerSign: |
    {% if tags.amenity == 'fountain' %}
    ⛲
    {% elseif tags.amenity == 'drinking_water' %}
    🚰
    {% endif %}
```

Roads, with different color depending on its priority:
```
type: overpass
name: 
  en: Roads # English name of the category
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
