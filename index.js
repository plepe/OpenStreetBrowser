var OverpassLayer = require('overpass-layer')
var OverpassLayerList = require('overpass-layer').List
var OverpassFrontend = require('overpass-frontend')

var map

window.onload = function() {
  map = L.map('map').setView([51.505, -0.09], 18)
  overpassFrontend = new OverpassFrontend('http://overpass.osm.rambler.ru/cgi/interpreter', {
    timeGap: 10,
    effortPerRequest: 100
  })

  var osm_mapnik = L.tileLayer('//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
    {
      maxZoom: 19,
      attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }
  )
  osm_mapnik.addTo(map)

  var overpass_layer = new OverpassLayer('node[natural=tree];', {
    style: {
          nodeFeature: 'CircleMarker',
          color: 'red',
          fillColor: 'red',
          fillOpacity: 0.1,
          weight: 1,
          radius: 6
    },
    marker: {
        iconUrl: 'img/map_pointer.png',
        iconSize: [ 25, 42 ],
        iconAnchor: [ 13, 42 ]
    },
    minZoom: 17,
    featureTitle: '{{ tags.species|default("Tree") }}',
    featureBody: function(ob) {
      return '<pre>' + JSON.stringify(ob.tags, null, '  ') + '</pre>'
    }
  })
  //overpass_layer.addTo(map)

  var overpass_layer2 = new OverpassLayer('(way[leisure=park];relation[leisure=park];);', {
    style: function(ob) {
      return {
          nodeFeature: 'CircleMarker',
          color: 'green',
          fillColor: 'green',
          fillOpacity: 0.2,
          weight: 1,
          radius: 6
      }
    },
    minZoom: 14,
    featureBody: function(ob) {
      return '<pre>' + JSON.stringify(ob.tags, null, '  ') + '</pre>'
    }
  })
  //overpass_layer2.addTo(map)

  var overpass_layer3 = new OverpassLayer('(node[amenity~"^(restaurant|cafe)$"];way[amenity~"^(restaurant|cafe)$"];relation[amenity~"^(restaurant|cafe)$"];);', {
    style:
      "{% if tags.cuisine == 'italian' %}\n" +
      "  color: #003f7f\n" +
      "  fillColor: #003f7f\n" +
      "{% else %}\n" +
      "  color: blue\n" +
      "  fillColor: blue  \n" +
      "{% endif %}\n" +
      "fillOpacity: 0.2\n" +
      "weight: 2\n" +
      "radius: 9\n",
    minZoom: 16,
    markerSign: '{% if tags.amenity=="restaurant" %}&#127860;{% else %}&#9749;{% endif %}',
    featureBody: "{{ tags.amenity }}<br/>Cuisine: {{ tags.cuisine|default('unknown') }}"
  })
  overpass_layer3.addTo(map)
  new OverpassLayerList(document.getElementById('info'), overpass_layer3);
}

